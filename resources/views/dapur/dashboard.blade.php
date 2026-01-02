@extends('layouts.app')

@section('title', 'Dashboard Dapur - Billiard Class')

@push('styles')
<style>
    .order-card {
        background: linear-gradient(135deg, #fa9a08 0%, #e88907 100%);
        border-radius: 16px;
        padding: 24px;
        color: white;
        box-shadow: 0 10px 30px rgba(250, 154, 8, 0.3);
    }
    
    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .order-status {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .order-timer {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 16px 0;
    }
    
    .timer-dot {
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .order-detail {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 12px 0;
        font-size: 14px;
    }
    
    .order-detail i {
        font-size: 18px;
        opacity: 0.9;
    }
    
    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .btn-complete {
        background: white;
        color: #fa9a08;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 14px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }
    
    .btn-complete:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
    }
</style>
@endpush

@section('content')
<div x-data="{ sidebarCollapsed: false, sidebarHover: false }" class="min-h-screen bg-slate-50 dark:bg-[#0A0A0A]">
    @include('dapur.partials.sidebar')
    
    <main :class="[(sidebarCollapsed && !sidebarHover) ? 'ml-20' : 'ml-72']" class="transition-all duration-300 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Dashboard Dapur</h1>
                <p class="text-slate-500 dark:text-gray-400">Kelola pesanan yang sedang diproses</p>
            </div>
            
            <div id="ordersContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($orders->count() > 0)
                    @foreach($orders as $order)
                        <div class="order-card" data-order-id="{{ $order->id }}">
                            <div class="order-card-header">
                                <div>
                                    <h3 class="text-lg font-bold">Order #{{ $order->id }}</h3>
                                    <p class="text-sm opacity-90">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                                </div>
                                <span class="order-status">SEDANG DIPROSES</span>
                            </div>
                            
                            <div class="order-timer">
                                <span class="timer-dot"></span>
                                <span class="font-bold" id="timer-{{ $order->id }}">00:00</span>
                            </div>
                            
                            <div class="order-detail">
                                <i class="ri-user-line"></i>
                                <span>{{ $order->customer_name }}</span>
                            </div>
                            
                            <div class="order-detail">
                                <i class="ri-table-line"></i>
                                <span>Meja {{ $order->table_number }}</span>
                            </div>
                            
                            <div class="order-detail">
                                <i class="ri-building-line"></i>
                                <span>{{ $order->room }}</span>
                            </div>
                            
                            <div class="order-detail">
                                <i class="ri-shopping-cart-line"></i>
                                <span>
                                    @foreach($order->orderItems as $item)
                                        {{ $item->quantity }}x {{ $item->menu_name }}@if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            </div>
                            
                            <div class="order-footer">
                                <div>
                                    <p class="text-sm opacity-90">Total Harga</p>
                                    <p class="text-2xl font-bold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                <button onclick="completeOrder({{ $order->id }})" class="btn-complete">
                                    <i class="ri-checkbox-circle-line mr-2"></i>
                                    Selesai
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-16">
                        <i class="ri-inbox-line text-6xl text-slate-400 dark:text-gray-600 mb-4"></i>
                        <p class="text-slate-500 dark:text-gray-400 text-lg">Tidak ada pesanan yang sedang diproses</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    let eventSource = null;
    let orderTimers = {};
    
    // Initialize timers for existing orders
    @foreach($orders as $order)
        startTimer({{ $order->id }}, '{{ $order->created_at->toIso8601String() }}');
    @endforeach
    
    // Function to start timer
    function startTimer(orderId, createdAt) {
        const startTime = new Date(createdAt);
        
        function updateTimer() {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            const minutes = Math.floor(diff / 60);
            const seconds = diff % 60;
            const timerEl = document.getElementById(`timer-${orderId}`);
            if (timerEl) {
                timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }
        
        updateTimer();
        orderTimers[orderId] = setInterval(updateTimer, 1000);
    }
    
    // Function to fetch active orders
    async function fetchActiveOrders() {
        try {
            const response = await fetch('/dapur/orders/active');
            const data = await response.json();
            
            if (response.ok && data.orders) {
                updateOrdersDisplay(data.orders);
            }
        } catch (error) {
            console.error('Error fetching orders:', error);
        }
    }
    
    // Function to update orders display
    function updateOrdersDisplay(orders) {
        const container = document.getElementById('ordersContainer');
        if (!container) return;
        
        if (orders.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-16">
                    <i class="ri-inbox-line text-6xl text-slate-400 dark:text-gray-600 mb-4"></i>
                    <p class="text-slate-500 dark:text-gray-400 text-lg">Tidak ada pesanan yang sedang diproses</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        orders.forEach(order => {
            const createdAt = new Date(order.created_at);
            const orderItems = order.order_items.map(item => `${item.quantity}x ${item.menu_name}`).join(', ');
            
            html += `
                <div class="order-card" data-order-id="${order.id}">
                    <div class="order-card-header">
                        <div>
                            <h3 class="text-lg font-bold">Order #${order.id}</h3>
                            <p class="text-sm opacity-90">${createdAt.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })} ${createdAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} WIB</p>
                        </div>
                        <span class="order-status">SEDANG DIPROSES</span>
                    </div>
                    
                    <div class="order-timer">
                        <span class="timer-dot"></span>
                        <span class="font-bold" id="timer-${order.id}">00:00</span>
                    </div>
                    
                    <div class="order-detail">
                        <i class="ri-user-line"></i>
                        <span>${order.customer_name}</span>
                    </div>
                    
                    <div class="order-detail">
                        <i class="ri-table-line"></i>
                        <span>Meja ${order.table_number}</span>
                    </div>
                    
                    <div class="order-detail">
                        <i class="ri-building-line"></i>
                        <span>${order.room}</span>
                    </div>
                    
                    <div class="order-detail">
                        <i class="ri-shopping-cart-line"></i>
                        <span>${orderItems}</span>
                    </div>
                    
                    <div class="order-footer">
                        <div>
                            <p class="text-sm opacity-90">Total Harga</p>
                            <p class="text-2xl font-bold">Rp${parseInt(order.total_price).toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="completeOrder(${order.id})" class="btn-complete">
                            <i class="ri-checkbox-circle-line mr-2"></i>
                            Selesai
                        </button>
                    </div>
                </div>
            `;
            
            // Start timer for this order
            if (!orderTimers[order.id]) {
                startTimer(order.id, order.created_at);
            }
        });
        
        container.innerHTML = html;
    }
    
    // Function to complete order
    async function completeOrder(orderId) {
        if (!confirm('Apakah pesanan ini sudah selesai?')) {
            return;
        }
        
        try {
            const response = await fetch(`/dapur/orders/${orderId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Remove order from display
                const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
                if (orderCard) {
                    orderCard.remove();
                }
                
                // Clear timer
                if (orderTimers[orderId]) {
                    clearInterval(orderTimers[orderId]);
                    delete orderTimers[orderId];
                }
                
                // Refresh orders
                fetchActiveOrders();
            } else {
                alert(data.message || 'Gagal menyelesaikan pesanan');
            }
        } catch (error) {
            console.error('Error completing order:', error);
            alert('Terjadi kesalahan saat menyelesaikan pesanan');
        }
    }
    
    // Connect to SSE
    function connectSSE() {
        if (eventSource) {
            eventSource.close();
        }
        
        eventSource = new EventSource('/dapur/orders/stream');
        
        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                if (data.type === 'new_orders' && data.orders) {
                    fetchActiveOrders();
                }
            } catch (error) {
                console.error('Error parsing SSE data:', error);
            }
        };
        
        eventSource.onerror = function(error) {
            console.error('SSE connection error:', error);
            eventSource.close();
            
            // Reconnect after 3 seconds
            setTimeout(connectSSE, 3000);
        };
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        fetchActiveOrders();
        connectSSE();
        
        // Update clock
        function updateClock() {
            const now = new Date();
            const clockEl = document.getElementById('sidebar-clock');
            if (clockEl) {
                clockEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
        }
        updateClock();
        setInterval(updateClock, 1000);
    });
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (eventSource) {
            eventSource.close();
        }
        Object.values(orderTimers).forEach(timer => clearInterval(timer));
    });
</script>
@endpush
@endsection

