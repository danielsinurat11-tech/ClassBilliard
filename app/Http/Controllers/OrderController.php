<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\order_items;
use App\Exports\OrdersExport;
use App\Mail\SendReportEmail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cash,qris,transfer',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.image' => 'nullable|string',
        ]);

        $totalPrice = 0;
        foreach ($request->items as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $order = orders::create([
            'customer_name' => $request->customer_name,
            'table_number' => $request->table_number,
            'room' => $request->room,
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);

        foreach ($request->items as $item) {
            order_items::create([
                'order_id' => $order->id,
                'menu_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'] ?? null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat',
            'order_id' => $order->id
        ]);
    }

    public function index()
    {
        $orders = orders::with('orderItems')
            ->where('status', 'processing')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dapur.dapur', compact('orders'));
    }

    /**
     * Admin: List all orders for management
     */
    public function adminIndex()
    {
        // Gabungkan semua orders menjadi satu list, urutkan berdasarkan waktu terbaru
        $allOrders = orders::with('orderItems')
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'processing' THEN 2 
                WHEN status = 'completed' THEN 3 
                WHEN status = 'rejected' THEN 4 
                ELSE 5 
            END")
            ->orderBy('created_at', 'desc')
            ->limit(100) // Limit untuk performa
            ->get();

        return view('admin.orders.index', compact('allOrders'));
    }

    /**
     * Admin: Approve order (change status from pending to processing)
     */
    public function approve($id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order ini tidak dapat diapprove karena statusnya bukan pending.');
        }

        $order->status = 'processing';
        $order->save();

        return back()->with('success', 'Order berhasil diapprove dan akan muncul di dapur.');
    }

    /**
     * Admin: Reject order
     */
    public function reject($id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order ini tidak dapat direject karena statusnya bukan pending.');
        }

        $order->status = 'rejected';
        $order->save();

        return back()->with('success', 'Order berhasil direject.');
    }

    /**
     * Admin: Delete order
     */
    public function destroy($id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Hapus order items terlebih dahulu
        $order->orderItems()->delete();
        
        // Hapus order
        $order->delete();

        return back()->with('success', 'Order berhasil dihapus.');
    }

    public function complete($id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        $order->status = 'completed';
        $order->save();

        // Return order data untuk realtime update
        return response()->json([
            'success' => true,
            'message' => 'Order telah diselesaikan',
            'order' => [
                'id' => $order->id,
                'customer_name' => $order->customer_name,
                'table_number' => $order->table_number,
                'room' => $order->room,
                'total_price' => $order->total_price,
                'payment_method' => $order->payment_method,
                'created_at' => $order->created_at->format('d M Y H:i'),
                'updated_at' => $order->updated_at->format('d M Y H:i'),
                'order_items' => $order->orderItems->map(function($item) {
                    return [
                        'menu_name' => $item->menu_name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ];
                })
            ]
        ]);
    }

    /**
     * Get active (processing) orders for live refresh on kitchen page.
     */
    public function activeOrders()
    {
        $orders = orders::with('orderItems')
            ->where('status', 'processing')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'created_at' => $order->created_at->toISOString(),
                    'updated_at' => $order->updated_at->toISOString(),
                    'order_items' => $order->orderItems->map(function ($item) {
                        return [
                            'menu_name' => $item->menu_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'image' => $item->image
                        ];
                    })
                ];
            })
        ]);
    }

    public function reports(Request $request)
    {
        $type = $request->get('type', 'daily'); // daily, monthly, yearly
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $year = $request->get('year', Carbon::now()->format('Y'));

        $query = orders::with('orderItems')
            ->where('status', 'completed');

        if ($type === 'daily') {
            $query->whereDate('updated_at', $date);
        } elseif ($type === 'monthly') {
            $query->whereYear('updated_at', Carbon::parse($month)->year)
                  ->whereMonth('updated_at', Carbon::parse($month)->month);
        } elseif ($type === 'yearly') {
            $query->whereYear('updated_at', $year);
        }

        $orders = $query->orderBy('updated_at', 'desc')->get();
        
        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        return response()->json([
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'order_items' => $order->orderItems->map(function($item) {
                        return [
                            'menu_name' => $item->menu_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'image' => $item->image
                        ];
                    })
                ];
            }),
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'type' => $type
        ]);
    }

    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'daily'); // daily, monthly, yearly
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $year = $request->get('year', Carbon::now()->format('Y'));

        $filename = 'Laporan_';
        if ($type === 'daily') {
            $filename .= 'Harian_' . Carbon::parse($date)->format('Y-m-d');
        } elseif ($type === 'monthly') {
            $filename .= 'Bulanan_' . Carbon::parse($month)->format('Y-m');
        } elseif ($type === 'yearly') {
            $filename .= 'Tahunan_' . $year;
        }
        $filename .= '.xlsx';

        return Excel::download(new OrdersExport($type, $date, $month, $year), $filename);
    }

    public function sendReportEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|in:daily,monthly,yearly',
            'date' => 'nullable|date',
            'month' => 'nullable|string',
            'year' => 'nullable|integer',
        ]);

        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $year = $request->get('year', Carbon::now()->format('Y'));

        // Generate filename
        $filename = 'Laporan_';
        $reportType = 'Harian';
        if ($type === 'daily') {
            $filename .= 'Harian_' . Carbon::parse($date)->format('Y-m-d');
            $reportType = 'Harian ' . Carbon::parse($date)->format('d M Y');
        } elseif ($type === 'monthly') {
            $filename .= 'Bulanan_' . Carbon::parse($month)->format('Y-m');
            $reportType = 'Bulanan ' . Carbon::parse($month)->format('F Y');
        } elseif ($type === 'yearly') {
            $filename .= 'Tahunan_' . $year;
            $reportType = 'Tahunan ' . $year;
        }
        $filename .= '.xlsx';

        try {
            // Check email configuration
            if (config('mail.default') === 'log') {
                Log::info('Email akan dikirim ke: ' . $request->email);
            }

            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Store Excel file temporarily
            $filePath = 'temp/' . $filename;
            Excel::store(new OrdersExport($type, $date, $month, $year), $filePath, 'local');

            // Get full path for attachment
            $fullPath = Storage::path($filePath);

            // Check if file exists
            if (!file_exists($fullPath)) {
                throw new \Exception('File Excel tidak berhasil dibuat');
            }

            // Log before sending
            Log::info('Attempting to send email', [
                'to' => $request->email,
                'file' => $filename,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
            ]);

            // Send email with attachment (sync)
            Mail::to($request->email)->send(new SendReportEmail($fullPath, $filename, $reportType));

            // Log after sending
            Log::info('Email sent successfully', [
                'to' => $request->email,
                'file' => $filename,
            ]);

            // Delete temporary file after sending
            Storage::delete($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Laporan Excel berhasil dikirim ke ' . $request->email . '. Silakan cek kotak masuk dan folder Spam Anda.'
            ]);
        } catch (\Exception $e) {
            // Delete temporary file if exists
            if (isset($filePath)) {
                Storage::delete($filePath);
            }

            Log::error('Email Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testEmail(Request $request)
    {
        $email = $request->get('email', 'test@example.com');
        
        // Get email config
        $mailConfig = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        Log::info('Test email configuration', $mailConfig);
        
        try {
            // Check if using log driver
            if (config('mail.default') === 'log') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email menggunakan log driver. Ubah MAIL_MAILER=smtp di .env',
                    'config' => $mailConfig,
                    'help' => 'Tambahkan di .env: MAIL_MAILER=smtp, MAIL_HOST=smtp.gmail.com, MAIL_PORT=587, MAIL_USERNAME=your-email@gmail.com, MAIL_PASSWORD=your-app-password, MAIL_ENCRYPTION=tls'
                ], 400);
            }

            // Check if config is empty
            if (empty(config('mail.mailers.smtp.host'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi email belum di-setup. Pastikan .env sudah dikonfigurasi dengan benar.',
                    'config' => $mailConfig,
                    'help' => 'Tambahkan di .env: MAIL_MAILER=smtp, MAIL_HOST=smtp.gmail.com, MAIL_PORT=587, MAIL_USERNAME=your-email@gmail.com, MAIL_PASSWORD=your-app-password, MAIL_ENCRYPTION=tls'
                ], 400);
            }

            Mail::raw('Ini adalah test email dari Billiard Class. Jika Anda menerima email ini, konfigurasi email sudah benar.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Billiard Class');
            });

            Log::info('Test email sent successfully', ['to' => $email]);

            return response()->json([
                'success' => true,
                'message' => 'Test email berhasil dikirim ke ' . $email . '. Silakan cek kotak masuk dan folder Spam.',
                'config' => $mailConfig,
                'tips' => [
                    '1. Cek folder Spam/Promosi di Gmail',
                    '2. Email mungkin memerlukan beberapa menit untuk sampai',
                    '3. Pastikan menggunakan App Password, bukan password Gmail biasa',
                    '4. Pastikan 2-Step Verification sudah aktif di Gmail'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config' => $mailConfig
            ]);

            $errorMessage = $e->getMessage();
            $helpMessage = '';

            if (strpos($errorMessage, 'Connection') !== false || strpos($errorMessage, 'could not be established') !== false) {
                $helpMessage = 'Pastikan MAIL_HOST dan MAIL_PORT benar. Untuk Gmail: smtp.gmail.com:587';
            } elseif (strpos($errorMessage, 'Authentication') !== false || strpos($errorMessage, 'login') !== false) {
                $helpMessage = 'Pastikan menggunakan App Password dari Gmail, bukan password biasa. Buat di: https://myaccount.google.com/apppasswords';
            } elseif (strpos($errorMessage, 'password') !== false) {
                $helpMessage = 'Pastikan MAIL_PASSWORD adalah App Password (16 karakter tanpa spasi) dari Gmail';
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim test email: ' . $errorMessage,
                'config' => $mailConfig,
                'help' => $helpMessage ?: 'Cek konfigurasi email di .env dan pastikan sudah benar'
            ], 500);
        }
    }
}

