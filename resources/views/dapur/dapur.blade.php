@extends('layouts.app')

@section('title', 'Dapur - Billiard Class')

{{-- Include shift calculation PHP block --}}
@include('dapur.partials.shift-calculation')

{{-- Include shift meta tags --}}
@include('dapur.partials.shift-meta')

{{-- Include common styles --}}
@include('dapur.partials.common-styles')

@push('styles')
<style>

    /* Modern Order Card Styles */
    .order-card-modern {
        position: relative;
        backdrop-filter: blur(10px);
    }

    .order-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .order-card-modern:hover::before {
        opacity: 1;
    }

    .order-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(250, 154, 8, 0.3);
    }

    .complete-order-btn {
        position: relative;
        overflow: hidden;
    }

    .complete-order-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(250, 154, 8, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .complete-order-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .complete-order-btn span {
        position: relative;
        z-index: 1;
    }

    .complete-order-btn i {
        position: relative;
        z-index: 1;
    }

    .sidebar {
        width: 280px;
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .sidebar.collapsed {
        transform: translateX(-100%);
    }
    .sidebar-desktop-collapsed {
        width: 80px;
    }
    .main-content {
        margin-left: 280px;
        transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    /* Hide scrollbar but keep functionality */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .main-content.expanded {
        margin-left: 0;
    }
    .main-content.desktop-collapsed {
        margin-left: 80px;
    }
    /* Order cards responsive */
    @media (max-width: 1024px) {
        .grid.grid-cols-\[repeat\(auto-fill\,minmax\(350px\,1fr\)\)\] {
            grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
        }
    }

    @media (max-width: 768px) {

        /* Order card mobile */
        .bg-\[#fa9a08\] {
            padding: 1rem !important;
        }


        /* Reports summary mobile */
        #reportsSummary {
            flex-direction: column;
        }

        #reportsSummary > div {
            width: 100%;
            min-width: auto;
        }

        /* Export buttons mobile */
        #exportExcelContainer {
            flex-direction: column;
        }

        #exportExcelContainer button {
            width: 100%;
        }

        /* Table responsive */
        .reports-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            min-width: 600px;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        /* Tablet styles */
        .grid.grid-cols-\[repeat\(auto-fill\,minmax\(350px\,1fr\)\)\] {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }

        .filter-input-group {
            flex-wrap: wrap;
        }

        #reportsSummary {
            flex-wrap: wrap;
        }

        #reportsSummary > div {
            flex: 1 1 calc(50% - 0.5rem);
            min-width: 200px;
        }
    }
    
    /* Notification Styles */
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
    
    .notification-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9998;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        visibility: hidden;
    }
    
    .notification-overlay.show {
        opacity: 1;
        pointer-events: auto;
        visibility: visible;
    }
    
    @media (min-width: 1024px) {
        .notification-overlay {
            display: none;
        }
    }
    
    .notification {
        background: linear-gradient(135deg, #fa9a08 0%, #ffb84d 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(250, 154, 8, 0.4);
        margin-bottom: 15px;
        animation: slideInRight 0.5s ease-out;
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 300px;
        position: relative;
        z-index: 9999;
    }
    
    @media (max-width: 640px) {
        .notification-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
        
        .notification {
            min-width: auto;
            width: 100%;
            padding: 16px;
        }
    }
    
    .notification.hide {
        animation: slideOutRight 0.5s ease-out forwards;
    }
    
    .notification-icon {
        font-size: 32px;
        animation: pulse 1s infinite;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 5px;
    }
    
    .notification-message {
        font-size: 14px;
        opacity: 0.95;
    }
    
    .notification-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    
    .notification-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
</style>
@endpush

@section('content')
    {{-- Logout Form --}}
    @include('dapur.partials.logout-form')

    {{-- Audio element for notification sound --}}
    <audio id="notificationSound" preload="auto" style="display: none;">
        <source id="notificationSoundSource" src="" type="audio/mpeg">
    </audio>

    {{-- Notification Overlay (Mobile Blur) --}}
    <div id="notificationOverlay" class="notification-overlay"></div>

    {{-- Notification Container --}}
    <div id="notificationContainer" class="notification-container"></div>

    <div class="flex min-h-screen bg-gray-50 dark:bg-[#050505] theme-transition text-black dark:text-slate-200" x-data="themeManager()" x-init="initTheme()">
        {{-- Sidebar --}}
        @include('dapur.partials.sidebar')

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full" :class="sidebarCollapsed ? 'desktop-collapsed' : ''">
            {{-- Navbar --}}
            @include('dapur.partials.navbar', ['pageTitle' => 'Dashboard Dapur'])

            <div class="flex-1 p-8 md:p-12 min-h-screen">
                <div class="w-full" id="ordersSection">
            @if($orders->count() > 0)
                <div class="grid grid-cols-[repeat(auto-fill,minmax(380px,1fr))] gap-6 max-md:grid-cols-1 max-md:gap-4">
                    @foreach($orders as $order)
                        <div class="order-card-modern group relative bg-gradient-to-br from-[#fa9a08] via-[#ff8c00] to-[#ff6b00] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-orange-300/20" data-order-id="{{ $order->id }}">
                            {{-- Decorative Pattern --}}
                            <div class="absolute inset-0 opacity-10">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -mr-16 -mt-16"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full -ml-12 -mb-12"></div>
                            </div>
                            
                            {{-- Card Header --}}
                            <div class="relative px-6 pt-6 pb-4 border-b border-white/20">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/30">
                                            <i class="ri-restaurant-line text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-white font-bold text-sm">Order #{{ $order->id }}</p>
                                            <p class="text-white/80 text-xs">{{ \Carbon\Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @php
                                            $minutesElapsed = \Carbon\Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->diffInMinutes(\Carbon\Carbon::now('Asia/Jakarta'));
                                            $isWarning = $minutesElapsed >= 15;
                                        @endphp
                                        @if($order->status === 'pending')
                                            <div class="px-3 py-1.5 bg-yellow-500/30 backdrop-blur-sm rounded-lg border border-yellow-500/50">
                                                <span class="text-yellow-200 text-xs font-bold uppercase tracking-wider">⏳ Belum Selesai</span>
                                            </div>
                                        @elseif($order->status === 'processing')
                                            <div class="px-3 py-1.5 bg-blue-500/30 backdrop-blur-sm rounded-lg border border-blue-500/50">
                                                <span class="text-blue-200 text-xs font-bold uppercase tracking-wider">🟡 Sedang Diproses</span>
                                            </div>
                                        @endif
                                        <div class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30">
                                            <span class="text-white text-xs font-bold uppercase tracking-wider">{{ $order->room }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Time Indicator --}}
                                @php
                                    $minutesElapsed = \Carbon\Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->diffInMinutes(\Carbon\Carbon::now('Asia/Jakarta'));
                                    $secondsElapsed = \Carbon\Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->diffInSeconds(\Carbon\Carbon::now('Asia/Jakarta'));
                                    $isWarning = $minutesElapsed >= 15;
                                    $stopwatchMinutes = floor($secondsElapsed / 60);
                                    $stopwatchSeconds = $secondsElapsed % 60;
                                @endphp
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex-1 h-1.5 rounded-full {{ $isWarning ? 'bg-red-500/50' : 'bg-white/20' }}">
                                        <div class="h-full rounded-full {{ $isWarning ? 'bg-red-500' : 'bg-white/40' }}" style="width: {{ min(100, ($minutesElapsed / 30) * 100) }}%"></div>
                                    </div>
                                    <span class="text-white/70 text-xs font-medium {{ $isWarning ? 'text-red-300 font-bold' : '' }} stopwatch-timer" data-order-id="{{ $order->id }}" data-start-time="{{ \Carbon\Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->timestamp }}">
                                        ⏱ <span class="stopwatch-display">{{ str_pad($stopwatchMinutes, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($stopwatchSeconds, 2, '0', STR_PAD_LEFT) }}</span>
                                    </span>
                                </div>
                                
                                {{-- Menu Items Preview --}}
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->orderItems->take(4) as $item)
                                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1 border border-white/30">
                                    <img src="{{ $item->image ? asset($item->image) : asset('assets/img/default.png') }}" 
                                         alt="{{ $item->menu_name }}" 
                                                 class="w-6 h-6 rounded-full object-cover border border-white/50"
                                         onerror="this.src='{{ asset('assets/img/default.png') }}'">
                                            <span class="text-white text-xs font-semibold">{{ $item->quantity }}x</span>
                                        </div>
                                @endforeach
                                    @if($order->orderItems->count() > 4)
                                        <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1 border border-white/30">
                                            <span class="text-white text-xs font-semibold">+{{ $order->orderItems->count() - 4 }}</span>
                            </div>
                                    @endif
                            </div>
                            </div>
                            
                            {{-- Card Body --}}
                            <div class="relative px-6 py-5 bg-white/5 backdrop-blur-sm">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                            <i class="ri-user-line text-white text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white/70 text-xs font-medium mb-0.5">Nama Pemesan</p>
                                            <p class="text-white font-bold text-sm">{{ $order->customer_name }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                            <i class="ri-table-line text-white text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white/70 text-xs font-medium mb-0.5">Meja</p>
                                            <p class="text-white font-bold text-sm">Meja {{ $order->table_number }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                            <i class="ri-shopping-bag-line text-white text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white/70 text-xs font-medium mb-1">Pesanan</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($order->orderItems as $item)
                                                    <span class="inline-block bg-white/20 backdrop-blur-sm px-2 py-1 rounded-md border border-white/30 text-white text-xs font-medium">
                                                        {{ $item->quantity }}x {{ $item->menu_name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Card Footer --}}
                            <div class="relative px-6 py-4 bg-white/10 backdrop-blur-sm border-t border-white/20 flex items-center justify-between">
                                <div>
                                    <p class="text-white/70 text-xs font-medium mb-0.5">Total Harga</p>
                                    <p class="text-white font-bold text-lg">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                @if($order->status === 'pending')
                                    <button class="start-cooking-btn bg-blue-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2 group/btn" data-order-id="{{ $order->id }}">
                                        <i class="ri-play-circle-line text-base group-hover/btn:scale-110 transition-transform"></i>
                                        <span>Mulai Masak</span>
                                    </button>
                                @elseif($order->status === 'processing')
                                    <button class="complete-order-btn bg-white text-[#fa9a08] px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2 group/btn" data-order-id="{{ $order->id }}">
                                        <i class="ri-checkbox-circle-line text-base group-hover/btn:rotate-12 transition-transform"></i>
                                        <span>Selesai</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 px-8 text-gray-600 dark:text-gray-500 text-lg">
                    <p>Belum ada pesanan</p>
                </div>
            @endif
        </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/dapur.js') }}"></script>
    <script>
        // Sidebar Toggle


        // Notification and Sound Functions
        const notificationSound = document.getElementById('notificationSound');
        const notificationContainer = document.getElementById('notificationContainer');
        const notificationOverlay = document.getElementById('notificationOverlay');
        let currentOrderIds = new Set();
        let isFirstLoad = true;
        let activeNotifications = new Set();

        // Load active notification sound from localStorage (set by dapur audio settings)
        async function loadActiveNotificationSound() {
            try {
                const savedAudio = localStorage.getItem('kitchenNotificationAudio');
                const audioType = localStorage.getItem('kitchenNotificationAudioType');
                
                const source = notificationSound ? notificationSound.querySelector('#notificationSoundSource') : null;
                
                if (!savedAudio || !source) {
                    // No audio selected, clear source
                    if (source) {
                        source.src = '';
                        if (notificationSound) {
                            notificationSound.pause();
                            notificationSound.currentTime = 0;
                        }
                    }
                    return;
                }
                
                if (audioType === 'database') {
                    // Load from database
                    const response = await fetch('/notification-sounds');
                    const sounds = await response.json();
                    const sound = sounds.find(s => s.filename === savedAudio);
                    if (sound) {
                        if (sound.file_path.startsWith('sounds/')) {
                            source.src = '{{ asset("storage") }}/' + sound.file_path;
                        } else {
                            source.src = '{{ asset("assets/sounds") }}/' + sound.filename;
                        }
                        notificationSound.load();
                    } else {
                        // Sound not found in database, clear
                        source.src = '';
                        if (notificationSound) {
                            notificationSound.pause();
                            notificationSound.currentTime = 0;
                        }
                        // Clear invalid localStorage
                        localStorage.removeItem('kitchenNotificationAudio');
                        localStorage.removeItem('kitchenNotificationAudioType');
                    }
                } else if (audioType === 'file') {
                    // File was selected directly, but file object is not persistent
                    // User needs to reselect on page reload
                    source.src = '';
                    if (notificationSound) {
                        notificationSound.pause();
                        notificationSound.currentTime = 0;
                    }
                    // Clear invalid localStorage
                    localStorage.removeItem('kitchenNotificationAudio');
                    localStorage.removeItem('kitchenNotificationAudioType');
                }
            } catch (error) {
                console.error('Error loading active notification sound:', error);
                // On error, clear audio source
                const source = notificationSound ? notificationSound.querySelector('#notificationSoundSource') : null;
                if (source) {
                    source.src = '';
                }
            }
        }

        // Load active sound on page load
        loadActiveNotificationSound();

        // Function to update overlay visibility
        function updateNotificationOverlay() {
            if (notificationOverlay) {
                // Check if there are any visible notifications
                const visibleNotifications = notificationContainer.querySelectorAll('.notification:not(.hide)');
                
                if (visibleNotifications.length > 0) {
                    notificationOverlay.classList.add('show');
                } else {
                    notificationOverlay.classList.remove('show');
                    // Force hide with visibility
                    notificationOverlay.style.visibility = 'hidden';
                }
            }
        }

        // Function to show notification
        function showNotification(order) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.id = `notification-${order.id}`;
            
            const itemsText = order.order_items.map(item => 
                `${item.quantity}x ${item.menu_name}`
            ).join(', ');
            
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="ri-notification-3-line"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">Pesanan Baru!</div>
                    <div class="notification-message">
                        <strong>${order.customer_name}</strong> - Meja ${order.table_number} (${order.room})<br>
                        ${itemsText}<br>
                        <strong>Total: Rp${parseInt(order.total_price).toLocaleString('id-ID')}</strong>
                    </div>
                </div>
                <button class="notification-close" onclick="closeNotification(${order.id})">
                    <i class="ri-close-line"></i>
                </button>
            `;
            
            notificationContainer.appendChild(notification);
            activeNotifications.add(order.id);
            
            // Small delay to ensure DOM is updated
            setTimeout(() => {
                updateNotificationOverlay();
            }, 10);
            
            // Auto close after 8 seconds
            setTimeout(() => {
                closeNotification(order.id);
            }, 8000);
            
            // Play sound only if audio is selected
            if (notificationSound) {
                const source = notificationSound.querySelector('#notificationSoundSource');
                // Only play if source has a valid src
                if (source && source.src && source.src !== '' && source.src !== window.location.href) {
                notificationSound.currentTime = 0;
                notificationSound.play().catch(err => {
                    console.log('Sound play failed:', err);
                });
                }
            }
        }

        // Function to close notification (global scope)
        window.closeNotification = function(orderId) {
            const notification = document.getElementById(`notification-${orderId}`);
            if (notification) {
                notification.classList.add('hide');
                activeNotifications.delete(orderId);
                
                // Update overlay immediately
                updateNotificationOverlay();
                
                setTimeout(() => {
                    notification.remove();
                    
                    // Final check - ensure overlay is hidden if no notifications
                    const remainingNotifications = notificationContainer.querySelectorAll('.notification:not(.hide)');
                    if (remainingNotifications.length === 0 && notificationOverlay) {
                        notificationOverlay.classList.remove('show');
                        notificationOverlay.style.visibility = 'hidden';
                        notificationOverlay.style.opacity = '0';
                    }
                }, 500);
            }
        }

        // Close overlay when clicked (mobile) - but prevent event bubbling
        if (notificationOverlay) {
            notificationOverlay.addEventListener('click', function(e) {
                // Only close if clicking directly on overlay, not on notification
                if (e.target === notificationOverlay) {
                    // Close all notifications
                    const notificationsToClose = Array.from(activeNotifications);
                    notificationsToClose.forEach(orderId => {
                        window.closeNotification(orderId);
                    });
                }
            });
        }
        
        // Prevent notification clicks from closing overlay
        if (notificationContainer) {
            notificationContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Function to render order card
        function renderOrderCard(order) {
            const items = order.order_items || [];
            const orderDate = new Date(order.created_at);
            const orderTime = orderDate.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Calculate minutes elapsed
            const now = new Date();
            const minutesElapsed = Math.floor((now - orderDate) / (1000 * 60));
            const isWarning = minutesElapsed >= 15;
            
            const previewItems = items.slice(0, 4);
            const remainingCount = items.length > 4 ? items.length - 4 : 0;
            
            return `
                <div class="order-card-modern group relative bg-gradient-to-br from-[#fa9a08] via-[#ff8c00] to-[#ff6b00] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-orange-300/20" data-order-id="${order.id}">
                    <!-- Decorative Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full -ml-12 -mb-12"></div>
                    </div>
                    
                    <!-- Card Header -->
                    <div class="relative px-6 pt-6 pb-4 border-b border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/30">
                                    <i class="ri-restaurant-line text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">Order #${order.id}</p>
                                    <p class="text-white/80 text-xs">${orderTime} WIB</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                ${order.status === 'pending' ? `
                                    <div class="px-3 py-1.5 bg-yellow-500/30 backdrop-blur-sm rounded-lg border border-yellow-500/50">
                                        <span class="text-yellow-200 text-xs font-bold uppercase tracking-wider">⏳ Belum Selesai</span>
                                    </div>
                                ` : order.status === 'processing' ? `
                                    <div class="px-3 py-1.5 bg-blue-500/30 backdrop-blur-sm rounded-lg border border-blue-500/50">
                                        <span class="text-blue-200 text-xs font-bold uppercase tracking-wider">🟡 Sedang Diproses</span>
                                    </div>
                                ` : ''}
                                <div class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30">
                                    <span class="text-white text-xs font-bold uppercase tracking-wider">${order.room}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Time Indicator -->
                        ${(() => {
                            const progressPercent = Math.min(100, (minutesElapsed / 30) * 100);
                            const totalSeconds = Math.floor((now - orderDate) / 1000);
                            const stopwatchMinutes = Math.floor(totalSeconds / 60);
                            const stopwatchSeconds = totalSeconds % 60;
                            const startTimestamp = Math.floor(orderDate.getTime() / 1000);
                            return `
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex-1 h-1.5 rounded-full ${isWarning ? 'bg-red-500/50' : 'bg-white/20'}">
                                        <div class="h-full rounded-full ${isWarning ? 'bg-red-500' : 'bg-white/40'}" style="width: ${progressPercent}%"></div>
                                    </div>
                                    <span class="text-white/70 text-xs font-medium ${isWarning ? 'text-red-300 font-bold' : ''} stopwatch-timer" data-order-id="${order.id}" data-start-time="${startTimestamp}">
                                        ⏱ <span class="stopwatch-display">${String(stopwatchMinutes).padStart(2, '0')}:${String(stopwatchSeconds).padStart(2, '0')}</span>
                                    </span>
                                </div>
                            `;
                        })()}
                        
                        <!-- Menu Items Preview -->
                        <div class="flex flex-wrap gap-2">
                            ${previewItems.map(item => `
                                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1 border border-white/30">
                            <img src="${item.image ? (item.image.startsWith('http') ? item.image : '/' + item.image) : '/assets/img/default.png'}" 
                                 alt="${item.menu_name}" 
                                         class="w-6 h-6 rounded-full object-cover border border-white/50"
                                 onerror="this.src='/assets/img/default.png'">
                                    <span class="text-white text-xs font-semibold">${item.quantity}x</span>
                                </div>
                        `).join('')}
                            ${remainingCount > 0 ? `
                                <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1 border border-white/30">
                                    <span class="text-white text-xs font-semibold">+${remainingCount}</span>
                    </div>
                            ` : ''}
                    </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="relative px-6 py-5 bg-white/5 backdrop-blur-sm">
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                    <i class="ri-user-line text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white/70 text-xs font-medium mb-0.5">Nama Pemesan</p>
                                    <p class="text-white font-bold text-sm">${order.customer_name}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                    <i class="ri-table-line text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white/70 text-xs font-medium mb-0.5">Meja</p>
                                    <p class="text-white font-bold text-sm">Meja ${order.table_number}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                    <i class="ri-shopping-bag-line text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white/70 text-xs font-medium mb-1">Pesanan</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        ${items.map(item => `
                                            <span class="inline-block bg-white/20 backdrop-blur-sm px-2 py-1 rounded-md border border-white/30 text-white text-xs font-medium">
                                                ${item.quantity}x ${item.menu_name}
                                            </span>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="relative px-6 py-4 bg-white/10 backdrop-blur-sm border-t border-white/20 flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-xs font-medium mb-0.5">Total Harga</p>
                            <p class="text-white font-bold text-lg">Rp${parseInt(order.total_price).toLocaleString('id-ID')}</p>
                        </div>
                        ${order.status === 'pending' ? `
                            <button class="start-cooking-btn bg-blue-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2 group/btn" data-order-id="${order.id}">
                                <i class="ri-play-circle-line text-base group-hover/btn:scale-110 transition-transform"></i>
                                <span>Mulai Masak</span>
                            </button>
                        ` : order.status === 'processing' ? `
                            <button class="complete-order-btn bg-white text-[#fa9a08] px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2 group/btn" data-order-id="${order.id}">
                                <i class="ri-checkbox-circle-line text-base group-hover/btn:rotate-12 transition-transform"></i>
                                <span>Selesai</span>
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        // Variable to store interval ID
        let refreshInterval = null;
        let refreshDelay = 3000; // Default refresh delay: 3 seconds

        // Function to start auto refresh
        function startAutoRefresh() {
            // Stop existing interval if any
            if (refreshInterval !== null) {
                clearInterval(refreshInterval);
            }
            
            // Start new interval with current delay
            refreshInterval = setInterval(fetchAndUpdateOrders, refreshDelay);
        }

        // Function to stop auto refresh
        function stopAutoRefresh() {
            if (refreshInterval !== null) {
                clearInterval(refreshInterval);
                refreshInterval = null;
            }
        }

        // Function to fetch and update orders
        async function fetchAndUpdateOrders() {
            try {
                const response = await fetch('/orders/active');
                const data = await response.json();
                
                if (!response.ok || !data.orders) {
                    // Continue refresh even on error (retry)
                    return;
                }
                
                const ordersSection = document.getElementById('ordersSection');
                if (!ordersSection) return;
                
                const newOrderIds = new Set(data.orders.map(o => o.id));
                let hasNewOrder = false;
                
                // Detect new orders
                if (!isFirstLoad) {
                    data.orders.forEach(order => {
                        if (!currentOrderIds.has(order.id)) {
                            // New order detected!
                            hasNewOrder = true;
                            showNotification(order);
                        }
                    });
                } else {
                    isFirstLoad = false;
                }
                
                // Update current order IDs
                currentOrderIds = newOrderIds;
                
                // Adjust refresh speed based on order status
                if (hasNewOrder) {
                    // New order detected - refresh faster (1 second)
                    refreshDelay = 1000;
                    stopAutoRefresh();
                    startAutoRefresh();
                    
                    // After 5 fast refreshes, return to normal speed
                    setTimeout(() => {
                        refreshDelay = 3000;
                        stopAutoRefresh();
                        startAutoRefresh();
                    }, 5000);
                } else if (data.orders.length > 0) {
                    // Has orders but no new ones - normal speed (3 seconds)
                    if (refreshDelay !== 3000) {
                        refreshDelay = 3000;
                        stopAutoRefresh();
                        startAutoRefresh();
                    }
                } else {
                    // No orders - slower refresh (5 seconds) but keep checking
                    if (refreshDelay !== 5000) {
                        refreshDelay = 5000;
                        stopAutoRefresh();
                        startAutoRefresh();
                    }
                }
                
                // Update orders display only if we're in orders section
                if (!ordersSection.classList.contains('hidden')) {
                    if (data.orders.length === 0) {
                        ordersSection.innerHTML = '<div class="text-center py-16 px-8 text-gray-600 dark:text-gray-500 text-lg"><p>Belum ada pesanan</p></div>';
                    } else {
                        const ordersGrid = ordersSection.querySelector('.grid');
                        if (ordersGrid) {
                            ordersGrid.innerHTML = data.orders.map(order => renderOrderCard(order)).join('');
                        } else {
                            ordersSection.innerHTML = `
                                <div class="grid grid-cols-[repeat(auto-fill,minmax(380px,1fr))] gap-6 max-md:grid-cols-1 max-md:gap-4">
                                    ${data.orders.map(order => renderOrderCard(order)).join('')}
                                </div>
                            `;
                        }
                        // Update stopwatches immediately after rendering
                        updateStopwatches();
                    }
                }
            } catch (error) {
                console.error('Error fetching orders:', error);
                // Stop refresh on error
                stopAutoRefresh();
            }
        }

        // Stopwatch timer function
        function updateStopwatches() {
            const now = Math.floor(Date.now() / 1000);
            document.querySelectorAll('.stopwatch-timer').forEach(timer => {
                const startTime = parseInt(timer.getAttribute('data-start-time'));
                const elapsedSeconds = now - startTime;
                const minutes = Math.floor(elapsedSeconds / 60);
                const seconds = elapsedSeconds % 60;
                
                const display = timer.querySelector('.stopwatch-display');
                if (display) {
                    display.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    
                    // Update warning color if >= 15 minutes
                    if (minutes >= 15) {
                        timer.classList.add('text-red-300', 'font-bold');
                        timer.classList.remove('text-white/70');
                    } else {
                        timer.classList.remove('text-red-300', 'font-bold');
                        timer.classList.add('text-white/70');
                    }
                    
                    // Update progress bar
                    const progressBar = timer.closest('.flex.items-center')?.querySelector('.h-full.rounded-full');
                    if (progressBar) {
                        const progressPercent = Math.min(100, (minutes / 30) * 100);
                        progressBar.style.width = `${progressPercent}%`;
                        
                        const progressContainer = progressBar.parentElement;
                        if (minutes >= 15) {
                            progressContainer.classList.remove('bg-white/20');
                            progressContainer.classList.add('bg-red-500/50');
                            progressBar.classList.remove('bg-white/40');
                            progressBar.classList.add('bg-red-500');
                        } else {
                            progressContainer.classList.remove('bg-red-500/50');
                            progressContainer.classList.add('bg-white/20');
                            progressBar.classList.remove('bg-red-500');
                            progressBar.classList.add('bg-white/40');
                        }
                    }
                }
            });
        }

        // Start stopwatch updates every second
        setInterval(updateStopwatches, 1000);
        
        
        {{-- Include shift check script --}}
        @include('dapur.partials.shift-check-script')
        
        // Initialize order IDs on page load
        (function() {
            const initialOrders = @json($orders);
            initialOrders.forEach(order => {
                currentOrderIds.add(order.id);
            });
            
            // Initial fetch after 1 second
            setTimeout(() => {
                fetchAndUpdateOrders();
                // Always start auto refresh (will adjust speed based on orders)
                startAutoRefresh();
            }, 1000);
        })();

        // Handle start cooking button
        document.addEventListener('click', async (e) => {
            if (e.target.closest('.start-cooking-btn')) {
                const button = e.target.closest('.start-cooking-btn');
                const orderId = button.getAttribute('data-order-id');
                
                if (!confirm('Mulai memproses pesanan ini?')) {
                    return;
                }

                try {
                    const response = await fetch(`/orders/${orderId}/start-cooking`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Refresh orders to update status
                        fetchAndUpdateOrders();
                    } else {
                        alert(result.message || 'Gagal memulai proses pesanan');
                    }
                } catch (error) {
                    console.error('Error starting cooking:', error);
                    alert('Terjadi kesalahan saat memulai proses pesanan');
                }
            }
        });

        // Handle complete order
        document.addEventListener('click', async (e) => {
            if (e.target.closest('.complete-order-btn')) {
                const button = e.target.closest('.complete-order-btn');
                const orderId = button.getAttribute('data-order-id');
                
                if (!confirm('Apakah pesanan ini sudah selesai?')) {
                    return;
                }

                try {
                    const response = await fetch(`/orders/${orderId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Remove order ID from tracking
                        currentOrderIds.delete(parseInt(orderId));
                        
                        // Remove order from orders section
                        const orderCard = button.closest('[data-order-id]');
                        if (orderCard) {
                            orderCard.style.transition = 'opacity 0.3s, transform 0.3s';
                            orderCard.style.opacity = '0';
                            orderCard.style.transform = 'translateX(-20px)';
                            setTimeout(() => {
                                orderCard.remove();
                                
                                // Check if no more orders
                                const ordersSection = document.getElementById('ordersSection');
                                const orderCards = ordersSection.querySelectorAll('[data-order-id]');
                                if (orderCards.length === 0) {
                                    ordersSection.innerHTML = '<div class="text-center py-16 px-8 text-gray-600 dark:text-gray-500 text-lg"><p>Belum ada pesanan</p></div>';
                                }
                            }, 300);
                        }

                        // Fetch latest orders to check if there are still active orders
                        fetchAndUpdateOrders();
                    } else {
                        alert(result.message || 'Gagal menyelesaikan pesanan');
                    }
                } catch (error) {
                    console.error('Error completing order:', error);
                    alert('Terjadi kesalahan saat menyelesaikan pesanan');
                }
            }
        });

    </script>

    {{-- Include theme manager script --}}
    @include('dapur.partials.theme-manager')

    @endpush
@endsection

