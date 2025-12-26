<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\order_items;
use App\Models\Report;
use App\Models\KitchenReport;
use App\Models\Shift;
use App\Exports\OrdersExport;
use App\Exports\RecapExport;
use App\Mail\SendReportEmail;
use App\Mail\SendRecapEmail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Get active shift based on current time
        $activeShift = Shift::getActiveShift();

        $order = orders::create([
            'customer_name' => $request->customer_name,
            'table_number' => $request->table_number,
            'room' => $request->room,
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'status' => 'processing', // Langsung processing agar langsung muncul di dapur tanpa perlu konfirmasi admin
            'shift_id' => $activeShift ? $activeShift->id : null
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
            'order_id' => $order->id,
            'redirect_url' => route('orders.show', $order->id)
        ]);
    }

    /**
     * Tampilkan halaman order detail untuk customer
     */
    public function show($id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Hanya bisa lihat order yang masih processing atau pending
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('menu')->with('error', 'Order sudah selesai dan tidak dapat diedit.');
        }
        
        return view('orders.show', compact('order'));
    }

    /**
     * Get order data as JSON (for loading into cart)
     */
    public function getOrderData($id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'customer_name' => $order->customer_name,
                'table_number' => $order->table_number,
                'room' => $order->room,
                'total_price' => $order->total_price,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'created_at' => $order->created_at->toISOString(),
                'updated_at' => $order->updated_at->toISOString(),
                'items' => $order->orderItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->menu_name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'image' => $item->image
                    ];
                })
            ]
        ]);
    }

    /**
     * Update order (tambah/kurang quantity item)
     */
    public function update(Request $request, $id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Hanya bisa edit order yang masih processing atau pending
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order sudah selesai dan tidak dapat diedit.'
            ], 400);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = 0;

            foreach ($request->items as $itemData) {
                $orderItem = order_items::where('id', $itemData['id'])
                    ->where('order_id', $order->id)
                    ->firstOrFail();

                $orderItem->quantity = $itemData['quantity'];
                $orderItem->save();

                $totalPrice += $orderItem->price * $orderItem->quantity;
            }

            // Update total price
            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil diupdate',
                'order' => $order->load('orderItems')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tambah item ke order yang sudah ada
     */
    public function addItem(Request $request, $id)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Hanya bisa edit order yang masih processing atau pending
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order sudah selesai dan tidak dapat diedit.'
            ], 400);
        }

        $request->validate([
            'menu_name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah item sudah ada di order
            $existingItem = order_items::where('order_id', $order->id)
                ->where('menu_name', $request->menu_name)
                ->first();

            if ($existingItem) {
                // Update quantity jika item sudah ada
                $existingItem->quantity += $request->quantity;
                $existingItem->save();
            } else {
                // Tambah item baru
                order_items::create([
                    'order_id' => $order->id,
                    'menu_name' => $request->menu_name,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'image' => $request->image ?? null
                ]);
            }

            // Recalculate total price
            $totalPrice = order_items::where('order_id', $order->id)
                ->get()
                ->sum(function($item) {
                    return $item->price * $item->quantity;
                });

            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan',
                'order' => $order->load('orderItems')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus item dari order
     */
    public function removeItem($id, $itemId)
    {
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Hanya bisa edit order yang masih processing atau pending
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order sudah selesai dan tidak dapat diedit.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $orderItem = order_items::where('id', $itemId)
                ->where('order_id', $order->id)
                ->firstOrFail();

            $orderItem->delete();

            // Recalculate total price
            $totalPrice = order_items::where('order_id', $order->id)
                ->get()
                ->sum(function($item) {
                    return $item->price * $item->quantity;
                });

            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus',
                'order' => $order->load('orderItems')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel($id)
    {
        $order = orders::findOrFail($id);
        
        // Hanya bisa cancel order yang masih pending atau processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order sudah selesai dan tidak dapat dibatalkan.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Hapus order items
            $order->orderItems()->delete();
            
            // Hapus order
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        // Get current user's shift
        $user = Auth::user();
        $userShiftId = $user->shift_id;
        
        // Get active shift based on time
        $activeShift = Shift::getActiveShift();
        
        $query = orders::with('orderItems')
            ->where('status', 'processing');
        
        // Filter by user's shift - WAJIB filter berdasarkan shift user
        if ($userShiftId) {
            $query->where('shift_id', $userShiftId);
        } else {
            // Jika user belum di-assign shift, tampilkan kosong
            return view('dapur.dapur', [
                'orders' => collect([]),
                'activeShift' => $activeShift
            ])->with('error', 'Anda belum di-assign ke shift. Silakan hubungi administrator.');
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('dapur.dapur', compact('orders', 'activeShift'));
    }

    /**
     * Admin: List all orders for management
     */
    public function adminIndex()
    {
        $user = Auth::user();
        
        // Filter orders berdasarkan shift user yang login
        $query = orders::with('orderItems');
        
        if ($user->shift_id) {
            // Hanya tampilkan order dari shift user yang login
            $query->where('shift_id', $user->shift_id);
        } else {
            // Jika user belum di-assign shift, tampilkan pesan error
            return view('admin.orders.index', [
                'allOrders' => collect([])
            ])->with('error', 'Anda belum di-assign ke shift. Silakan hubungi administrator.');
        }
        
        // Urutkan berdasarkan status dan waktu terbaru
        $allOrders = $query->orderByRaw("CASE 
                WHEN status = 'processing' THEN 1 
                WHEN status = 'completed' THEN 2 
                WHEN status = 'rejected' THEN 3 
                WHEN status = 'pending' THEN 4 
                ELSE 5 
            END")
            ->orderBy('created_at', 'desc')
            ->limit(100) // Limit untuk performa
            ->get();

        return view('admin.orders.index', compact('allOrders'));
    }

    /**
     * Check for new orders or status changes (for auto refresh)
     */
    public function checkNewOrders(Request $request)
    {
        $user = Auth::user();
        $lastOrderId = $request->get('last_order_id', 0);
        $lastCheckTime = $request->get('last_check_time');
        
        // Filter berdasarkan shift user yang login
        if (!$user->shift_id) {
            return response()->json([
                'has_changes' => false,
                'has_new_orders' => false,
                'has_status_changes' => false,
                'new_orders_count' => 0,
                'status_changed_count' => 0,
                'latest_order_id' => 0,
                'current_time' => Carbon::now('Asia/Jakarta')->toISOString()
            ]);
        }
        
        // Check for new orders (order baru langsung processing, jadi tidak perlu check pending)
        $newOrdersCount = orders::where('shift_id', $user->shift_id)
            ->where('id', '>', $lastOrderId)
            ->where('status', 'processing')
            ->count();
        
        // Check for status changes (orders that were updated after last check)
        $statusChangedCount = 0;
        if ($lastCheckTime) {
            $statusChangedCount = orders::where('shift_id', $user->shift_id)
                ->where('updated_at', '>', $lastCheckTime)
                ->where(function($query) {
                    $query->where('status', 'completed')
                          ->orWhere('status', 'rejected')
                          ->orWhere('status', 'processing');
                })
                ->count();
        }
        
        $latestOrder = orders::where('shift_id', $user->shift_id)
            ->orderBy('id', 'desc')
            ->first();
        $latestOrderId = $latestOrder ? $latestOrder->id : 0;
        
        // Check if there are any changes (new orders or status changes)
        $hasChanges = $newOrdersCount > 0 || $statusChangedCount > 0;
        
        return response()->json([
            'has_changes' => $hasChanges,
            'has_new_orders' => $newOrdersCount > 0,
            'has_status_changes' => $statusChangedCount > 0,
            'new_orders_count' => $newOrdersCount,
            'status_changed_count' => $statusChangedCount,
            'latest_order_id' => $latestOrderId,
            'current_time' => Carbon::now('Asia/Jakarta')->toISOString()
        ]);
    }

    /**
     * Admin: Approve order (change status from pending to processing)
     */
    public function approve($id)
    {
        $user = Auth::user();
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Pastikan user hanya bisa approve order dari shift mereka sendiri
        if ($order->shift_id != $user->shift_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke order ini.');
        }
        
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
        $user = Auth::user();
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Pastikan user hanya bisa reject order dari shift mereka sendiri
        if ($order->shift_id != $user->shift_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke order ini.');
        }
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order ini tidak dapat direject karena statusnya bukan pending.');
        }

        $order->status = 'rejected';
        $order->save();
        
        return back()->with('success', 'Order berhasil direject.');
    }

    /**
     * Admin: Delete order
     * 
     * PENTING: Hanya bisa menghapus order dengan status pending.
     * Order yang sudah completed, processing, atau rejected TIDAK BOLEH DIHAPUS
     * untuk mencegah kecurangan saat tutup buku akhir bulan/tahun.
     * Data penjualan harus tersimpan permanen untuk keperluan audit dan laporan keuangan.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Pastikan user hanya bisa hapus order dari shift mereka sendiri
        if ($order->shift_id != $user->shift_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke order ini.');
        }
        
        // Proteksi: Order yang sudah completed, processing, atau rejected tidak bisa dihapus
        // Ini untuk mencegah kecurangan saat tutup buku akhir bulan/tahun
        if (!$order->canBeDeleted()) {
            $statusLabels = [
                'completed' => 'SELESAI',
                'processing' => 'SEDANG DIPROSES',
                'rejected' => 'DITOLAK'
            ];
            $statusLabel = $statusLabels[$order->status] ?? strtoupper($order->status);
            
            return back()->with('error', 'Order dengan status ' . $statusLabel . ' tidak dapat dihapus. Data penjualan harus tersimpan permanen untuk keperluan tutup buku dan audit.');
        }
        
        // Hapus order items terlebih dahulu
        $order->orderItems()->delete();
        
        // Hapus order
        $order->delete();

        return back()->with('success', 'Order berhasil dihapus.');
    }

    public function complete($id)
    {
        $user = Auth::user();
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Pastikan user hanya bisa complete order dari shift mereka sendiri
        if ($order->shift_id != $user->shift_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke order ini.'
            ], 403);
        }
        
        $order->status = 'completed';
        $order->save();

        // Simpan ke kitchen_reports agar tidak hilang saat logout
        try {
            // Cek apakah sudah ada di kitchen_reports (untuk menghindari duplikasi)
            $existingReport = KitchenReport::where('order_id', $order->id)->first();
            
            if (!$existingReport) {
                // Simpan order items sebagai JSON
                $orderItems = $order->orderItems->map(function($item) {
                    return [
                        'menu_name' => $item->menu_name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'image' => $item->image
                    ];
                })->toArray();

                KitchenReport::create([
                    'order_id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'order_items' => $orderItems,
                    'order_date' => Carbon::parse($order->created_at)->format('Y-m-d'),
                    'completed_at' => Carbon::now('Asia/Jakarta')
                ]);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan proses complete
            Log::error('Error saving to kitchen_reports: ' . $e->getMessage());
        }

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
                'created_at' => Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB',
                'updated_at' => Carbon::parse($order->updated_at)->utc()->setTimezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB',
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
        // Get current user's shift
        $user = Auth::user();
        $userShiftId = $user->shift_id;
        
        // Get active shift based on time
        $activeShift = Shift::getActiveShift();
        
        $query = orders::with('orderItems')
            ->where('status', 'processing');
        
        // Filter by user's shift - WAJIB filter berdasarkan shift user
        if ($userShiftId) {
            $query->where('shift_id', $userShiftId);
        } else {
            // Jika user belum di-assign shift, return kosong
            return response()->json([
                'orders' => []
            ]);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'created_at' => Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->toISOString(),
                    'updated_at' => Carbon::parse($order->updated_at)->utc()->setTimezone('Asia/Jakarta')->toISOString(),
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

        // Get current user's shift - WAJIB filter berdasarkan shift user
        $user = Auth::user();
        if (!$user->shift_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum di-assign ke shift. Silakan hubungi administrator.',
                'orders' => [],
                'total_revenue' => 0,
                'total_orders' => 0
            ], 400);
        }
        
        $shiftId = $user->shift_id; // Selalu gunakan shift user yang login

        // Ambil dari kitchen_reports (data yang sudah tersimpan permanen)
        $kitchenReportsQuery = KitchenReport::query();
        
        // Filter by shift if specified - KitchenReport has order_id, so we need to join with orders
        if ($shiftId) {
            $kitchenReportsQuery->whereHas('order', function($q) use ($shiftId) {
                $q->where('shift_id', $shiftId);
            });
        }

        if ($type === 'daily') {
            $kitchenReportsQuery->whereDate('order_date', $date);
        } elseif ($type === 'monthly') {
            $kitchenReportsQuery->whereYear('order_date', Carbon::parse($month)->year)
                  ->whereMonth('order_date', Carbon::parse($month)->month);
        } elseif ($type === 'yearly') {
            $kitchenReportsQuery->whereYear('order_date', $year);
        }

        $kitchenReports = $kitchenReportsQuery->orderBy('completed_at', 'desc')->get();
        
        // Also filter kitchen reports by shift_id if specified
        if ($shiftId) {
            $kitchenReportOrderIds = orders::where('shift_id', $shiftId)->pluck('id')->toArray();
            $kitchenReports = $kitchenReports->filter(function($report) use ($kitchenReportOrderIds) {
                return in_array($report->order_id, $kitchenReportOrderIds);
            });
        }

        // Ambil juga dari orders untuk data yang mungkin belum tersimpan di kitchen_reports
        $ordersQuery = orders::with('orderItems')
            ->where('status', 'completed');
        
        // Filter by shift if specified
        if ($shiftId) {
            $ordersQuery->where('shift_id', $shiftId);
        }

        if ($type === 'daily') {
            $ordersQuery->whereDate('updated_at', $date);
        } elseif ($type === 'monthly') {
            $ordersQuery->whereYear('updated_at', Carbon::parse($month)->year)
                  ->whereMonth('updated_at', Carbon::parse($month)->month);
        } elseif ($type === 'yearly') {
            $ordersQuery->whereYear('updated_at', $year);
        }

        $orders = $ordersQuery->orderBy('updated_at', 'desc')->get();

        // Gabungkan data dari kitchen_reports dan orders
        // Gunakan kitchen_reports sebagai sumber utama, lalu tambahkan dari orders yang belum ada
        $kitchenReportOrderIds = $kitchenReports->pluck('order_id')->toArray();
        
        $allOrders = collect();
        
        // Tambahkan dari kitchen_reports
        foreach ($kitchenReports as $report) {
            $allOrders->push([
                'id' => $report->order_id,
                'customer_name' => $report->customer_name,
                'table_number' => $report->table_number,
                'room' => $report->room,
                'total_price' => $report->total_price,
                'payment_method' => $report->payment_method,
                'created_at' => $report->completed_at->toISOString(),
                'updated_at' => $report->completed_at->toISOString(),
                'order_items' => $report->order_items
            ]);
        }
        
        // Tambahkan dari orders yang belum ada di kitchen_reports
        foreach ($orders as $order) {
            if (!in_array($order->id, $kitchenReportOrderIds)) {
                $allOrders->push([
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'created_at' => $order->created_at->toISOString(),
                    'updated_at' => $order->updated_at->toISOString(),
                    'order_items' => $order->orderItems->map(function($item) {
                        return [
                            'menu_name' => $item->menu_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'image' => $item->image
                        ];
                    })
                ]);
            }
        }

        // Urutkan berdasarkan updated_at/completed_at terbaru
        $allOrders = $allOrders->sortByDesc(function($order) {
            return $order['updated_at'];
        })->values();

        $totalRevenue = $allOrders->sum('total_price');
        $totalOrders = $allOrders->count();

        return response()->json([
            'orders' => $allOrders->toArray(),
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'type' => $type,
            'shift_id' => $shiftId
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

    /**
     * Rekapitulasi laporan - menggabungkan order yang sudah completed menjadi satu rekapan
     * Setelah di-rekap, order akan dihapus dari histori
     */
    public function recap(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = Auth::user();
        
        if (!$user->shift_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum di-assign ke shift. Silakan hubungi administrator.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Ambil semua order yang sudah completed dalam periode tertentu DAN sesuai shift user
            $orders = orders::with('orderItems')
                ->where('status', 'completed')
                ->where('shift_id', $user->shift_id) // Filter berdasarkan shift user
                ->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ])
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada order yang bisa di-rekap untuk periode ini. Pastikan ada order dengan status completed untuk periode yang dipilih.'
                ], 400);
            }

            // Hitung total revenue berdasarkan payment method
            $totalRevenue = $orders->sum('total_price');
            $cashRevenue = $orders->where('payment_method', 'cash')->sum('total_price');
            $qrisRevenue = $orders->where('payment_method', 'qris')->sum('total_price');
            $transferRevenue = $orders->where('payment_method', 'transfer')->sum('total_price');

            // Cek apakah sudah ada rekapitulasi dengan periode yang sama DAN shift yang sama
            $existingReport = Report::where('shift_id', $user->shift_id)
                ->where('start_date', Carbon::parse($request->start_date)->format('Y-m-d'))
                ->where('end_date', Carbon::parse($request->end_date)->format('Y-m-d'))
                ->first();

            // Buat ringkasan order (simpan detail order sebagai JSON)
            $orderSummary = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'created_at' => Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'menu_name' => $item->menu_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                        ];
                    })
                ];
            })->toArray();

            if ($existingReport) {
                // Jika sudah ada rekapitulasi dengan periode yang sama, gabungkan
                $existingOrderIds = collect($existingReport->order_summary)->pluck('id')->toArray();
                $existingOrders = collect($existingReport->order_summary);
                
                // Filter order baru yang belum ada di rekapitulasi
                $newOrders = collect($orderSummary)->reject(function ($order) use ($existingOrderIds) {
                    return in_array($order['id'], $existingOrderIds);
                })->toArray();
                
                // Gabungkan order yang sudah ada dengan order baru
                $mergedOrderSummary = array_merge($existingOrders->toArray(), $newOrders);
                
                // Hitung ulang statistik
                $mergedTotalOrders = count($mergedOrderSummary);
                $mergedTotalRevenue = collect($mergedOrderSummary)->sum('total_price');
                $mergedCashRevenue = collect($mergedOrderSummary)->where('payment_method', 'cash')->sum('total_price');
                $mergedQrisRevenue = collect($mergedOrderSummary)->where('payment_method', 'qris')->sum('total_price');
                $mergedTransferRevenue = collect($mergedOrderSummary)->where('payment_method', 'transfer')->sum('total_price');
                
                // Update rekapitulasi yang sudah ada
                $existingReport->update([
                    'total_orders' => $mergedTotalOrders,
                    'total_revenue' => $mergedTotalRevenue,
                    'cash_revenue' => $mergedCashRevenue,
                    'qris_revenue' => $mergedQrisRevenue,
                    'transfer_revenue' => $mergedTransferRevenue,
                    'order_summary' => $mergedOrderSummary,
                ]);
                
                $report = $existingReport;
                $message = 'Rekapitulasi berhasil digabung dengan rekapitulasi yang sudah ada. ' . count($newOrders) . ' order baru ditambahkan.';
            } else {
                // Buat rekapitulasi baru
                $report = Report::create([
                    'report_date' => Carbon::now(),
                    'start_date' => Carbon::parse($request->start_date),
                    'end_date' => Carbon::parse($request->end_date),
                    'total_orders' => $orders->count(),
                    'total_revenue' => $totalRevenue,
                    'cash_revenue' => $cashRevenue,
                    'qris_revenue' => $qrisRevenue,
                    'transfer_revenue' => $transferRevenue,
                    'order_summary' => $orderSummary,
                    'created_by' => Auth::user()->name ?? 'System',
                    'shift_id' => $user->shift_id
                ]);
                $message = 'Rekapitulasi berhasil dibuat. ' . $orders->count() . ' order telah di-rekap.';
            }

            // Hapus order yang sudah di-rekap beserta order items-nya
            $orderIds = $orders->pluck('id');
            order_items::whereIn('order_id', $orderIds)->delete();
            orders::whereIn('id', $orderIds)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating recap: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat rekapitulasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan halaman rekapitulasi
     */
    public function recapIndex()
    {
        $user = Auth::user();
        
        if (!$user->shift_id) {
            return view('admin.orders.recap', [
                'reports' => collect([])->paginate(20)
            ])->with('error', 'Anda belum di-assign ke shift. Silakan hubungi administrator.');
        }

        // Hanya tampilkan rekapitulasi dari shift user yang login
        $reports = Report::where('shift_id', $user->shift_id)
            ->orderBy('report_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.recap', compact('reports'));
    }

    /**
     * Get detail rekapitulasi untuk modal
     */
    public function recapDetail($id)
    {
        $user = Auth::user();
        $report = Report::findOrFail($id);
        
        // Pastikan user hanya bisa melihat rekapitulasi shift mereka sendiri
        if ($report->shift_id != $user->shift_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke rekapitulasi ini.'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'order_summary' => $report->order_summary
        ]);
    }

    /**
     * Update rekapitulasi yang sudah ada
     */
    public function updateRecap(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $report = Report::findOrFail($id);

            // Cek apakah periode baru sama dengan rekapitulasi lain (selain yang sedang diupdate)
            $existingReportWithSamePeriod = Report::where('id', '!=', $id)
                ->where('start_date', Carbon::parse($request->start_date)->format('Y-m-d'))
                ->where('end_date', Carbon::parse($request->end_date)->format('Y-m-d'))
                ->first();

            // Ambil semua order yang sudah completed dalam periode baru
            // Catatan: Order yang sudah di-rekap sebelumnya sudah dihapus,
            // jadi kita hanya bisa mengambil order baru yang completed untuk periode tersebut
            $orders = orders::with('orderItems')
                ->where('status', 'completed')
                ->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ])
                ->get();

            // Gabungkan dengan order yang sudah ada di rekapitulasi sebelumnya
            $existingOrderIds = collect($report->order_summary)->pluck('id')->toArray();
            $existingOrders = collect($report->order_summary);
            
            // Jika ada rekapitulasi lain dengan periode yang sama, gabungkan juga
            if ($existingReportWithSamePeriod) {
                $otherReportOrderIds = collect($existingReportWithSamePeriod->order_summary)->pluck('id')->toArray();
                $otherReportOrders = collect($existingReportWithSamePeriod->order_summary);
                
                // Gabungkan order dari rekapitulasi lain (yang belum ada di rekapitulasi ini)
                $otherOrdersToMerge = $otherReportOrders->reject(function ($order) use ($existingOrderIds) {
                    return in_array($order['id'], $existingOrderIds);
                });
                
                // Tambahkan ke existing orders
                $existingOrders = $existingOrders->merge($otherOrdersToMerge);
                $existingOrderIds = $existingOrders->pluck('id')->toArray();
            }

            // Ambil order baru yang belum ada di rekapitulasi
            $newOrders = $orders->reject(function ($order) use ($existingOrderIds) {
                return in_array($order->id, $existingOrderIds);
            });

            // Buat order summary baru (gabungkan yang lama dengan yang baru)
            $newOrderSummary = $existingOrders->map(function ($order) {
                return [
                    'id' => $order['id'],
                    'customer_name' => $order['customer_name'],
                    'table_number' => $order['table_number'],
                    'room' => $order['room'],
                    'total_price' => $order['total_price'],
                    'payment_method' => $order['payment_method'],
                    'created_at' => $order['created_at'],
                    'items' => $order['items']
                ];
            })->toArray();
            
            // Jika ada rekapitulasi lain dengan periode yang sama, hapus rekapitulasi tersebut setelah digabung
            if ($existingReportWithSamePeriod) {
                $existingReportWithSamePeriod->delete();
            }

            // Tambahkan order baru jika ada
            foreach ($newOrders as $order) {
                $newOrderSummary[] = [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'created_at' => Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'menu_name' => $item->menu_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                        ];
                    })
                ];
            }

            // Jika tidak ada order sama sekali (baik yang lama maupun yang baru)
            if (empty($newOrderSummary)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada order yang bisa di-rekap untuk periode ini. Pastikan ada order dengan status completed untuk periode yang dipilih.'
                ], 400);
            }

            // Hitung ulang statistik berdasarkan order_summary yang baru
            $totalOrders = count($newOrderSummary);
            $totalRevenue = collect($newOrderSummary)->sum('total_price');
            $cashRevenue = collect($newOrderSummary)->where('payment_method', 'cash')->sum('total_price');
            $qrisRevenue = collect($newOrderSummary)->where('payment_method', 'qris')->sum('total_price');
            $transferRevenue = collect($newOrderSummary)->where('payment_method', 'transfer')->sum('total_price');

            // Update rekapitulasi
            $report->update([
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date),
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'cash_revenue' => $cashRevenue,
                'qris_revenue' => $qrisRevenue,
                'transfer_revenue' => $transferRevenue,
                'order_summary' => $newOrderSummary,
            ]);

            // Hapus order baru yang sudah ditambahkan ke rekapitulasi
            if ($newOrders->isNotEmpty()) {
                $newOrderIds = $newOrders->pluck('id');
                order_items::whereIn('order_id', $newOrderIds)->delete();
                orders::whereIn('id', $newOrderIds)->delete();
            }

            DB::commit();

            $message = 'Rekapitulasi berhasil diperbarui.';
            if ($newOrders->count() > 0) {
                $message .= ' ' . $newOrders->count() . ' order baru ditambahkan.';
            } else {
                $message .= ' Periode rekapitulasi telah diupdate.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'report' => $report->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating recap: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui rekapitulasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download rekapitulasi sebagai Excel
     */
    public function exportRecap($id)
    {
        $user = Auth::user();
        $report = Report::findOrFail($id);
        
        // Pastikan user hanya bisa export rekapitulasi shift mereka sendiri
        if ($report->shift_id != $user->shift_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke rekapitulasi ini.');
        }
        
        $startDate = Carbon::parse($report->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($report->end_date)->format('Y-m-d');
        $filename = 'Rekapitulasi_' . $startDate . '_s_d_' . $endDate . '.xlsx';

        return Excel::download(new RecapExport($report), $filename);
    }

    /**
     * Kirim rekapitulasi via email
     */
    public function sendRecapEmail(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = Auth::user();
        $report = Report::findOrFail($id);
        
        // Pastikan user hanya bisa kirim rekapitulasi shift mereka sendiri
        if ($report->shift_id != $user->shift_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke rekapitulasi ini.'
            ], 403);
        }
        
        $startDate = Carbon::parse($report->start_date)->format('d M Y');
        $endDate = Carbon::parse($report->end_date)->format('d M Y');
        $reportPeriod = $startDate . ' - ' . $endDate;
        
        $filename = 'Rekapitulasi_' . Carbon::parse($report->start_date)->format('Y-m-d') . '_s_d_' . Carbon::parse($report->end_date)->format('Y-m-d') . '.xlsx';

        try {
            // Check email configuration
            if (config('mail.default') === 'log') {
                Log::info('Email rekapitulasi akan dikirim ke: ' . $request->email);
            }

            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Store Excel file temporarily
            $filePath = 'temp/' . $filename;
            Excel::store(new RecapExport($report), $filePath, 'local');

            // Get full path for attachment
            $fullPath = Storage::path($filePath);

            // Check if file exists
            if (!file_exists($fullPath)) {
                throw new \Exception('File Excel tidak berhasil dibuat');
            }

            // Log before sending
            Log::info('Attempting to send recap email', [
                'to' => $request->email,
                'file' => $filename,
                'mailer' => config('mail.default'),
            ]);

            // Send email with attachment
            Mail::to($request->email)->send(new SendRecapEmail($fullPath, $filename, $reportPeriod));

            // Log after sending
            Log::info('Recap email sent successfully', [
                'to' => $request->email,
                'file' => $filename,
            ]);

            // Delete temporary file after sending
            Storage::delete($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Rekapitulasi berhasil dikirim ke ' . $request->email . '. Silakan cek kotak masuk dan folder Spam Anda.'
            ]);
        } catch (\Exception $e) {
            // Delete temporary file if exists
            if (isset($filePath)) {
                Storage::delete($filePath);
            }

            Log::error('Recap Email Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }
}

