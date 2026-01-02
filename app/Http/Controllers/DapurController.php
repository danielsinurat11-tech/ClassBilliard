<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\order_items;
use App\Models\KitchenReport;
use App\Models\Menu;
use App\Models\FoodInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DapurController extends Controller
{
    /**
     * Display kitchen dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get active orders for current shift
        $query = orders::with('orderItems')
            ->where('status', 'processing')
            ->orderBy('created_at', 'desc');
        
        // Apply shift filter
        if (!$user->hasRole('super_admin') && $user->shift_id) {
            $query->where('shift_id', $user->shift_id);
        }
        
        $orders = $query->get();
        
        return view('dapur.dashboard', compact('orders'));
    }

    /**
     * Get active orders (API endpoint)
     */
    public function activeOrders()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['orders' => []], 401);
        }
        
        // Get active orders
        $query = orders::select('id', 'customer_name', 'table_number', 'room', 'total_price', 'payment_method', 'status', 'created_at', 'updated_at', 'shift_id')
            ->with('orderItems:id,order_id,menu_name,price,quantity,image')
            ->where('status', 'processing')
            ->orderBy('created_at', 'desc');
        
        // Apply shift filter
        if (!$user->hasRole('super_admin') && $user->shift_id) {
            $query->where('shift_id', $user->shift_id);
        }
        
        $orders = $query->get();
        
        return response()->json([
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'status' => $order->status,
                    'created_at' => $order->created_at->toIso8601String(),
                    'updated_at' => $order->updated_at->toIso8601String(),
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

    /**
     * Server-Sent Events endpoint untuk real-time orders
     */
    public function ordersStream()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->stream(function () use ($user) {
            $lastOrderId = 0;
            $checkInterval = 0.5; // Check every 500ms
            
            while (true) {
                if (connection_aborted()) {
                    break;
                }

                // Get latest orders
                $query = orders::select('id', 'customer_name', 'table_number', 'room', 'total_price', 'payment_method', 'status', 'created_at', 'updated_at', 'shift_id')
                    ->with('orderItems:id,order_id,menu_name,price,quantity,image')
                    ->whereIn('status', ['pending', 'processing'])
                    ->where('id', '>', $lastOrderId)
                    ->orderBy('created_at', 'asc');
                
                // Apply shift filter
                if (!$user->hasRole('super_admin') && $user->shift_id) {
                    $query->where('shift_id', $user->shift_id);
                }
                
                $orders = $query->get();
                
                if ($orders->count() > 0) {
                    $ordersData = $orders->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'customer_name' => $order->customer_name,
                            'table_number' => $order->table_number,
                            'room' => $order->room,
                            'total_price' => $order->total_price,
                            'payment_method' => $order->payment_method,
                            'status' => $order->status,
                            'created_at' => $order->created_at->toIso8601String(),
                            'updated_at' => $order->updated_at->toIso8601String(),
                            'order_items' => $order->orderItems->map(function ($item) {
                                return [
                                    'menu_name' => $item->menu_name,
                                    'price' => $item->price,
                                    'quantity' => $item->quantity,
                                    'image' => $item->image
                                ];
                            })
                        ];
                    });
                    
                    $lastOrderId = $orders->max('id');
                    
                    echo "data: " . json_encode([
                        'type' => 'new_orders',
                        'orders' => $ordersData
                    ]) . "\n\n";
                    
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                } else {
                    // Send heartbeat
                    echo ": heartbeat\n\n";
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
                
                usleep($checkInterval * 1000000);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Start cooking order
     */
    public function startCooking($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $order = orders::findOrFail($id);
        
        // Authorization
        if (!$user->hasRole('super_admin') && $order->shift_id !== $user->shift_id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke order ini.'], 403);
        }
        
        if ($order->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Order ini tidak dapat dimulai karena statusnya bukan pending.'], 400);
        }
        
        $order->update(['status' => 'processing']);
        
        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dimulai.',
            'order' => $order->fresh()
        ]);
    }

    /**
     * Complete order
     */
    public function complete($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $order = orders::with('orderItems')->findOrFail($id);
        
        // Authorization
        if (!$user->hasRole('super_admin') && $order->shift_id !== $user->shift_id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke order ini.'], 403);
        }
        
        // Update status
        $order->update(['status' => 'completed']);

        // Reduce inventory stock for each order item
        try {
            foreach ($order->orderItems as $orderItem) {
                // Find menu by name (order_items stores menu_name, not menu_id)
                $menu = Menu::where('name', $orderItem->menu_name)->first();
                
                if ($menu) {
                    // Get inventory for this menu
                    $inventory = FoodInventory::where('menu_id', $menu->id)->first();
                    
                    if ($inventory) {
                        // Calculate new quantity
                        $newQuantity = max(0, $inventory->quantity - $orderItem->quantity);
                        
                        // Update inventory
                        $inventory->update([
                            'quantity' => $newQuantity,
                            'last_restocked_at' => now() // Track when stock was last updated
                        ]);
                        
                        // Log if stock goes below reorder level
                        if ($inventory->isBelowReorderLevel()) {
                            Log::warning('Inventory below reorder level', [
                                'menu_id' => $menu->id,
                                'menu_name' => $menu->name,
                                'current_quantity' => $newQuantity,
                                'reorder_level' => $inventory->reorder_level,
                                'order_id' => $order->id
                            ]);
                        }
                    } else {
                        // Menu doesn't have inventory tracking - log for admin attention
                        Log::info('Menu has no inventory tracking', [
                            'menu_id' => $menu->id,
                            'menu_name' => $menu->name,
                            'order_id' => $order->id
                        ]);
                    }
                } else {
                    // Menu not found - might have been deleted
                    Log::warning('Menu not found for order item', [
                        'menu_name' => $orderItem->menu_name,
                        'order_id' => $order->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error reducing inventory stock', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't fail the order completion if inventory update fails
            // Just log the error for admin to fix manually
        }

        // Save to kitchen_reports
        try {
            $existingReport = KitchenReport::where('order_id', $order->id)->first();
            
            if (!$existingReport) {
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
                    'order_items' => json_encode($orderItems),
                    'order_date' => $order->created_at->format('Y-m-d'),
                    'completed_at' => Carbon::now('Asia/Jakarta')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error saving to kitchen_reports', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

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
                'created_at' => $order->created_at->format('d M Y H:i') . ' WIB',
                'updated_at' => $order->updated_at->format('d M Y H:i') . ' WIB',
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
}

