@extends('layouts.app')

@section('title', 'Dapur - Billiard Class')

@php
    $isDarkMode = request()->cookie('theme') === 'dark' || (!request()->cookie('theme') && isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark');
@endphp

@push('styles')
<style>
    .sidebar {
        width: 280px;
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
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
    .sidebar-menu-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .sidebar-menu-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    .sidebar-menu-item.active {
        background-color: #fa9a08;
        color: #000 !important;
        font-weight: 600;
    }
    .sidebar-menu-item.active i {
        color: #000 !important;
    }
    .sidebar-menu-item.active span {
        color: #000 !important;
    }
    .sidebar-menu-item {
        display: flex;
        align-items: center;
    }
    /* Responsive Styles for Tablet and Mobile */
    @media (max-width: 1024px) {
        .sidebar {
            position: fixed;
            z-index: 9999;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 280px;
        }
        .sidebar:not(.collapsed) {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }

        /* Order cards responsive */
        .grid.grid-cols-\[repeat\(auto-fill\,minmax\(350px\,1fr\)\)\] {
            grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 260px;
        }

        .main-content {
            padding: 0.75rem;
        }

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
    {{-- Audio element for notification sound --}}
    <audio id="notificationSound" preload="auto" style="display: none;">
        <source id="notificationSoundSource" src="" type="audio/mpeg">
    </audio>

    {{-- Notification Overlay (Mobile Blur) --}}
    <div id="notificationOverlay" class="notification-overlay"></div>

    {{-- Notification Container --}}
    <div id="notificationContainer" class="notification-container"></div>

    <div class="flex min-h-screen bg-white dark:bg-[#050505]" x-data="themeManager()" x-init="initTheme()">
        {{-- Sidebar --}}
        <aside id="sidebar" 
            @mouseenter="if(sidebarCollapsed) sidebarHover = true" 
            @mouseleave="sidebarHover = false"
            class="sidebar fixed lg:static top-0 left-0 h-screen bg-gradient-to-b from-gray-50 to-white dark:from-[#0A0A0A] dark:to-[#050505] border-r border-gray-200 dark:border-white/10 z-50 flex flex-col shadow-sm dark:shadow-none"
            :class="[
                (sidebarCollapsed && !sidebarHover) ? 'sidebar-desktop-collapsed' : '',
                (sidebarCollapsed && sidebarHover) ? 'shadow-[20px_0_50px_rgba(0,0,0,0.5)]' : ''
            ]">
            {{-- Sidebar Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-[#0A0A0A] dark:to-[#1a1a1a]">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#fa9a08] to-[#ffb84d] rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20 shrink-0">
                            <i class="ri-restaurant-line text-white text-xl"></i>
                        </div>
                        <h2 x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms class="text-xl font-bold text-gray-900 dark:text-slate-200 whitespace-nowrap">Dashboard Dapur</h2>
                    </div>
                    <button id="sidebar-toggle" class="lg:hidden text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="flex items-center gap-2" x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms>
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <p class="text-gray-600 dark:text-gray-500 text-sm">Selamat datang, <span class="text-gray-900 dark:text-slate-200 font-medium">{{ Auth::user()->name ?? 'User' }}</span></p>
                </div>
            </div>

            {{-- Sidebar Menu --}}
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto overflow-x-hidden no-scrollbar">
                <a href="{{ route('dapur') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('dapur') ? 'active' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="ri-shopping-cart-2-line text-lg shrink-0"></i>
                    <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Orderan</span>
                </a>
                <a href="{{ route('reports') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('reports') ? 'active' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="ri-file-chart-2-line text-lg shrink-0"></i>
                    <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Laporan</span>
                </a>
                <a href="{{ route('pengaturan-audio') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('pengaturan-audio') ? 'active' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="ri-settings-3-line text-lg shrink-0"></i>
                    <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Pengaturan Audio</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-gray-200 dark:border-white/10 bg-white dark:bg-[#050505]">
                {{-- Sidebar Toggle Button --}}
                <button @click="sidebarCollapsed = !sidebarCollapsed; sidebarHover = false" 
                    class="w-full h-9 flex items-center justify-center rounded-md bg-white/5 hover:bg-[#fa9a08] hover:text-black transition-all group">
                    <i :class="sidebarCollapsed ? 'ri-arrow-right-s-line' : 'ri-arrow-left-s-line'" class="text-sm"></i>
                    </button>
            </div>
        </aside>

        {{-- Sidebar Overlay untuk Mobile --}}
        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full" :class="sidebarCollapsed ? 'desktop-collapsed' : ''">
            {{-- Header dengan Profile dan Theme Toggle --}}
            <header class="h-16 px-6 flex items-center justify-between sticky top-0 z-40 bg-white/80 dark:bg-[#050505]/80 backdrop-blur-md border-b border-gray-200 dark:border-white/10">
                <div class="flex items-center gap-4">
                    {{-- Mobile Sidebar Toggle --}}
                    <button id="mobile-sidebar-toggle" class="lg:hidden text-gray-900 dark:text-slate-200">
                    <i class="ri-menu-line text-2xl"></i>
                </button>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-200 hidden lg:block">Dashboard Dapur</h2>
            </div>

                        <div class="flex items-center gap-4">
                    {{-- Theme Switcher --}}
                    <button @click="toggleTheme()"
                        class="w-8 h-8 rounded-md border border-gray-300 dark:border-white/10 flex items-center justify-center hover:border-[#fa9a08] transition-all">
                        <i x-show="!darkMode" class="ri-moon-line text-sm text-gray-900 dark:text-slate-200"></i>
                        <i x-show="darkMode" class="ri-sun-line text-sm text-[#fa9a08]" x-cloak></i>
                            </button>

                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2.5 group">
                            <img class="w-8 h-8 rounded-md object-cover border border-white/10"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name) }}&background=fa9a08&color=000&bold=true"
                                alt="">
                            <div class="text-left hidden md:block">
                                <p class="text-[11px] font-bold text-gray-900 dark:text-slate-200 leading-none group-hover:text-[#fa9a08] transition-colors">
                                    {{ Auth::user()?->name }}</p>
                        </div>
                            </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-3 w-52 bg-white dark:bg-[#0A0A0A] border border-gray-200 dark:border-white/10 rounded-lg shadow-xl p-1 z-50">
                            <button @click="handleLogout()"
                                class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-xs font-bold text-red-400 hover:bg-red-500/10 transition-all text-left">
                                <i class="ri-logout-box-r-line text-sm"></i> Sign Out
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 max-md:p-4 bg-gray-50 dark:bg-transparent min-h-screen">
                <div class="w-full" id="ordersSection">
            @if($orders->count() > 0)
                <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-6 max-md:grid-cols-1 max-md:gap-4">
                    @foreach($orders as $order)
                        <div class="bg-[#fa9a08] border-2 border-amber-400 rounded-2xl p-6 flex flex-col gap-4 relative transition-[transform,box-shadow] duration-200 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.3)] max-md:p-5" data-order-id="{{ $order->id }}">
                            <div class="flex flex-wrap gap-3 justify-start items-center mb-2">
                                @foreach($order->orderItems as $item)
                                    <img src="{{ $item->image ? asset($item->image) : asset('assets/img/default.png') }}" 
                                         alt="{{ $item->menu_name }}" 
                                         class="w-[60px] h-[60px] rounded-full object-cover border-2 border-white bg-white max-md:w-[50px] max-md:h-[50px]"
                                         onerror="this.src='{{ asset('assets/img/default.png') }}'">
                                @endforeach
                            </div>
                            <div class="text-white text-[0.95rem] leading-relaxed max-md:text-[0.85rem]">
                                <p class="my-2"><strong class="font-semibold">Waktu Pesan :</strong> {{ \Carbon\Carbon::parse($order->created_at)->utc()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>
                                <p class="my-2"><strong class="font-semibold">Nama Pemesan :</strong> {{ $order->customer_name }}</p>
                                <p class="my-2"><strong class="font-semibold">Pesanan :</strong> 
                                    {{ $order->orderItems->map(function($item){ return $item->quantity . 'x ' . $item->menu_name; })->implode(', ') }}
                                </p>
                                <p class="my-2"><strong class="font-semibold">Nomor Meja :</strong> {{ $order->table_number }}</p>
                                <p class="my-2"><strong class="font-semibold">Ruangan :</strong> {{ $order->room }}</p>
                                <p class="my-2"><strong class="font-semibold">Total Harga :</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <button class="self-end bg-white text-[#fa9a08] border-none rounded-lg py-2 px-6 text-[0.9rem] font-semibold cursor-pointer transition-all duration-200 mt-2 hover:bg-slate-100 hover:scale-105 active:scale-95 max-md:py-2 max-md:px-5 max-md:text-[0.85rem]" data-order-id="{{ $order->id }}">Selesai</button>
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
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainContent = document.querySelector('.main-content');

        function toggleSidebar() {
            const isMobile = window.innerWidth <= 1024;
            if (isMobile) {
                const isCollapsed = sidebar.classList.contains('collapsed');
                if (isCollapsed) {
                    // Open sidebar
                    sidebar.classList.remove('collapsed');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.add('show');
                    }
                    // Prevent body scroll when sidebar is open
                    document.body.style.overflow = 'hidden';
                } else {
                    // Close sidebar
                    sidebar.classList.add('collapsed');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('show');
                    }
                    // Restore body scroll
                    document.body.style.overflow = '';
                }
            }
        }

        // Initialize sidebar as collapsed on mobile
        if (window.innerWidth <= 1024) {
            sidebar.classList.add('collapsed');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        // Close sidebar when window is resized to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('collapsed');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('show');
                }
                document.body.style.overflow = '';
            } else {
                // Ensure sidebar is collapsed on mobile
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                }
            }
        });


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
            const itemsText = items.map(i => `${i.quantity}x ${i.menu_name}`).join(', ');
            const orderTime = new Date(order.created_at).toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            return `
                <div class="bg-[#fa9a08] border-2 border-amber-400 rounded-2xl p-6 flex flex-col gap-4 relative transition-[transform,box-shadow] duration-200 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.3)] max-md:p-5" data-order-id="${order.id}">
                    <div class="flex flex-wrap gap-3 justify-start items-center mb-2">
                        ${items.map(item => `
                            <img src="${item.image ? (item.image.startsWith('http') ? item.image : '/' + item.image) : '/assets/img/default.png'}" 
                                 alt="${item.menu_name}" 
                                 class="w-[60px] h-[60px] rounded-full object-cover border-2 border-white bg-white max-md:w-[50px] max-md:h-[50px]"
                                 onerror="this.src='/assets/img/default.png'">
                        `).join('')}
                    </div>
                    <div class="text-white text-[0.95rem] leading-relaxed max-md:text-[0.85rem]">
                        <p class="my-2"><strong class="font-semibold">Waktu Pesan :</strong> ${orderTime}</p>
                        <p class="my-2"><strong class="font-semibold">Nama Pemesan :</strong> ${order.customer_name}</p>
                        <p class="my-2"><strong class="font-semibold">Pesanan :</strong> ${itemsText}</p>
                        <p class="my-2"><strong class="font-semibold">Nomor Meja :</strong> ${order.table_number}</p>
                        <p class="my-2"><strong class="font-semibold">Ruangan :</strong> ${order.room}</p>
                        <p class="my-2"><strong class="font-semibold">Total Harga :</strong> Rp${parseInt(order.total_price).toLocaleString('id-ID')}</p>
                    </div>
                    <button class="self-end bg-white text-[#fa9a08] border-none rounded-lg py-2 px-6 text-[0.9rem] font-semibold cursor-pointer transition-all duration-200 mt-2 hover:bg-slate-100 hover:scale-105 active:scale-95 max-md:py-2 max-md:px-5 max-md:text-[0.85rem]" data-order-id="${order.id}">Selesai</button>
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
                                <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-6 max-md:grid-cols-1 max-md:gap-4">
                                    ${data.orders.map(order => renderOrderCard(order)).join('')}
                                </div>
                            `;
                        }
                    }
                }
            } catch (error) {
                console.error('Error fetching orders:', error);
                // Stop refresh on error
                stopAutoRefresh();
            }
        }

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

        // Handle complete order
        document.addEventListener('click', async (e) => {
            if (e.target.closest('button[data-order-id]')) {
                const button = e.target.closest('button[data-order-id]');
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
                    }
                } catch (error) {
                    console.error('Error completing order:', error);
                    alert('Terjadi kesalahan saat menyelesaikan pesanan');
                }
            }
        });

    </script>

    {{-- Hidden Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    {{-- Theme Manager Script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                sidebarCollapsed: false,
                sidebarHover: false,
                darkMode: false, // Will be set in initTheme()

                initTheme() {
                    // Set initial theme based on localStorage or system preference
                    const savedTheme = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    
                    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                        this.darkMode = true;
                        document.documentElement.classList.add('dark');
                    } else {
                        this.darkMode = false;
                        document.documentElement.classList.remove('dark');
                    }
                    
                    console.log('Theme initialized:', this.darkMode ? 'dark' : 'light', 'Saved:', savedTheme);
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    // Force re-render untuk memastikan perubahan terlihat
                    this.$nextTick(() => {
                        console.log('Theme toggled:', this.darkMode ? 'dark' : 'light');
                    });
                },

                updateTheme() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },

                handleLogout() {
                        Swal.fire({
                        title: 'Confirm Logout',
                        text: 'Are you sure you want to logout?',
                        icon: 'warning',
                        showCancelButton: true,
                            confirmButtonColor: '#fa9a08',
                        cancelButtonColor: '#1e1e1e',
                        confirmButtonText: 'Yes, Sign Out',
                        background: this.darkMode ? '#0A0A0A' : '#fff',
                        color: this.darkMode ? '#fff' : '#000',
                            customClass: {
                            popup: 'rounded-lg border border-white/5',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                            }
                        });
                    }
            }));
        });
    </script>

    @endpush
@endsection

