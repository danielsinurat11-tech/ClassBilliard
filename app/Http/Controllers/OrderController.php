<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\order_items;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
            ->where('status', '!=', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dapur', compact('orders'));
    }

    public function complete($id)
    {
        $order = orders::findOrFail($id);
        $order->status = 'completed';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order telah diselesaikan'
        ]);
    }

    /**
     * Get active (pending) orders for live refresh on kitchen page.
     */
    public function activeOrders()
    {
        $orders = orders::with('orderItems')
            ->where('status', '!=', 'completed')
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
                    'created_at' => $order->created_at,
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
}

