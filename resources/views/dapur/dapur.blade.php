@extends('layouts.app')

@section('title', 'Dapur - Billiard Class')

@push('styles')
<style>
    .sidebar {
        width: 280px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: hidden;
    }
    .sidebar.collapsed {
        transform: translateX(-100%);
    }
    .main-content {
        margin-left: 280px;
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100vh;
        overflow-y: auto;
    }
    .main-content.expanded {
        margin-left: 0;
    }
    .sidebar-menu-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .sidebar-menu-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
        transform: translateX(4px);
    }
    .sidebar-menu-item.active {
        background: linear-gradient(90deg, rgba(250, 154, 8, 0.15) 0%, rgba(250, 154, 8, 0.05) 100%);
        border-left: 4px solid #fa9a08;
        color: #fa9a08;
        font-weight: 600;
    }
    .sidebar-menu-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #fa9a08 0%, #ffb84d 100%);
        border-radius: 0 4px 4px 0;
    }
    .sidebar-menu-item i {
        transition: transform 0.2s ease;
    }
    .sidebar-menu-item:hover i {
        transform: scale(1.1);
    }
    .sidebar-menu-item.active i {
        color: #fa9a08;
    }
    /* Responsive Styles for Tablet and Mobile */
    @media (max-width: 1024px) {
        .sidebar {
            position: fixed;
            z-index: 9999;
            height: 100vh;
            overflow-y: auto;
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

        /* Filter tabs mobile */
        .filter-tab-btn {
            padding: 0.75rem 1rem !important;
            font-size: 0.85rem !important;
        }

        /* Filter inputs mobile */
        .filter-input-group {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .filter-input-group label {
            min-width: auto !important;
        }

        .filter-input-group input {
            width: 100% !important;
            max-width: 100% !important;
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
    <audio id="notificationSound" preload="auto">
        <source src="{{ asset('assets/sounds/new_order.mp3') }}" type="audio/mpeg">
    </audio>

    {{-- Notification Overlay (Mobile Blur) --}}
    <div id="notificationOverlay" class="notification-overlay"></div>

    {{-- Notification Container --}}
    <div id="notificationContainer" class="notification-container"></div>

    <div class="flex min-h-screen bg-black">
        {{-- Sidebar --}}
        <aside id="sidebar" class="sidebar fixed lg:static top-0 left-0 h-screen bg-gradient-to-b from-[#1a1a1a] to-[#0f0f0f] border-r border-white/10 z-50 flex flex-col">
            {{-- Sidebar Header --}}
            <div class="p-6 border-b border-white/10 bg-gradient-to-r from-[#1a1a1a] to-[#252525]">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#fa9a08] to-[#ffb84d] rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <i class="ri-restaurant-line text-white text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Dashboard Dapur</h2>
                    </div>
                    <button id="sidebar-toggle" class="lg:hidden text-gray-400 hover:text-white transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <p class="text-gray-400 text-sm">Selamat datang, <span class="text-white font-medium">{{ Auth::user()->name ?? 'User' }}</span></p>
                </div>
            </div>

            {{-- Sidebar Menu --}}
            <nav class="flex-1 p-4 space-y-2 overflow-hidden">
                <a href="#" id="menu-orders" class="sidebar-menu-item active flex items-center gap-4 px-4 py-3.5 rounded-xl text-white cursor-pointer group">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="ri-shopping-cart-2-line text-2xl"></i>
                    </div>
                    <span class="font-semibold text-base">Orderan</span>
                </a>
                <a href="#" id="menu-reports" class="sidebar-menu-item flex items-center gap-4 px-4 py-3.5 rounded-xl text-gray-400 hover:text-white cursor-pointer group">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="ri-file-chart-2-line text-2xl"></i>
                    </div>
                    <span class="font-semibold text-base">Laporan</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-white/10 bg-[#0f0f0f]">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-red-500/20 hover:shadow-red-500/30 hover:scale-[1.02] active:scale-[0.98]">
                        <i class="ri-logout-box-r-line text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Sidebar Overlay untuk Mobile --}}
        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full">
            {{-- Mobile Header dengan Toggle --}}
            <div class="lg:hidden bg-[#1a1a1a] p-4 border-b border-white/10 flex items-center justify-between">
                <button id="mobile-sidebar-toggle" class="text-white">
                    <i class="ri-menu-line text-2xl"></i>
                </button>
                <h2 class="text-lg font-bold text-white">Dashboard Dapur</h2>
                <div class="w-8"></div>
            </div>

            <div class="p-6 max-md:p-4">
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
                <div class="text-center py-16 px-8 text-gray-400 text-lg">
                    <p>Belum ada pesanan</p>
                </div>
            @endif
        </div>

        <div class="w-full hidden" id="reportsSection">
            <div class="bg-[#1a1a1a] p-6 rounded-2xl mb-6">
                <div class="flex gap-2 mb-6">
                    <button class="filter-tab-btn py-3 px-6 bg-[#2a2a2a] border-none text-white text-[0.9rem] font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#3a3a3a] active:bg-[#fa9a08] active:text-white" data-filter-type="daily">Harian</button>
                    <button class="filter-tab-btn py-3 px-6 bg-[#2a2a2a] border-none text-white text-[0.9rem] font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#3a3a3a]" data-filter-type="monthly">Bulanan</button>
                    <button class="filter-tab-btn py-3 px-6 bg-[#2a2a2a] border-none text-white text-[0.9rem] font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#3a3a3a]" data-filter-type="yearly">Tahunan</button>
                </div>
                
                <div class="flex flex-col gap-4">
                    <div class="filter-input-group flex items-center gap-4 flex-wrap" id="dailyFilter">
                        <label for="dailyDate" class="text-white font-medium min-w-[120px]">Pilih Tanggal:</label>
                        <input type="date" id="dailyDate" class="py-3 px-3 bg-[#2a2a2a] border border-[#555] rounded-lg text-white text-[0.95rem] flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" value="{{ date('Y-m-d') }}">
                        <button class="load-reports-btn py-3 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-[0.95rem] font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="daily">Tampilkan</button>
                    </div>
                    
                    <div class="filter-input-group items-center gap-4 flex-wrap hidden" id="monthlyFilter">
                        <label for="monthlyDate" class="text-white font-medium min-w-[120px]">Pilih Bulan:</label>
                        <input type="month" id="monthlyDate" class="py-3 px-3 bg-[#2a2a2a] border border-[#555] rounded-lg text-white text-[0.95rem] flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" value="{{ date('Y-m') }}">
                        <button class="load-reports-btn py-3 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-[0.95rem] font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="monthly">Tampilkan</button>
                    </div>
                    
                    <div class="filter-input-group items-center gap-4 flex-wrap hidden" id="yearlyFilter">
                        <label for="yearlyDate" class="text-white font-medium min-w-[120px]">Pilih Tahun:</label>
                        <input type="number" id="yearlyDate" class="py-3 px-3 bg-[#2a2a2a] border border-[#555] rounded-lg text-white text-[0.95rem] flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" min="2020" max="2099" value="{{ date('Y') }}">
                        <button class="load-reports-btn py-3 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-[0.95rem] font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="yearly">Tampilkan</button>
                    </div>
                </div>
            </div>

            <div class="gap-4 mb-6 items-center flex-wrap flex hidden" id="reportsSummary">
                <div class="bg-[#fa9a08] p-6 rounded-xl flex-1 min-w-[200px]">
                    <h3 class="text-white text-[0.9rem] font-medium m-0 mb-2">Total Pesanan</h3>
                    <p id="totalOrders" class="text-white text-2xl font-bold m-0">0</p>
                </div>
                <div class="bg-[#fa9a08] p-6 rounded-xl flex-1 min-w-[200px]">
                    <h3 class="text-white text-[0.9rem] font-medium m-0 mb-2">Total Pendapatan</h3>
                    <p id="totalRevenue" class="text-white text-2xl font-bold m-0">Rp0</p>
                </div>
            </div>

            {{-- Export Buttons - Always visible in reports section --}}
            <div class="mb-6 hidden flex gap-4" id="exportExcelContainer">
                <button class="send-report-btn py-3 px-6 bg-blue-500 text-white border-none rounded-xl text-[0.95rem] font-semibold cursor-pointer transition-all duration-200 hover:bg-blue-600 flex items-center gap-2" id="sendReportBtn">
                    <i class="ri-mail-line"></i>
                    Kirim Laporan
                </button>
                <button class="download-report-btn py-3 px-6 bg-emerald-500 text-white border-none rounded-xl text-[0.95rem] font-semibold cursor-pointer transition-all duration-200 hover:bg-emerald-600 flex items-center gap-2" id="downloadReportBtn">
                    <i class="ri-download-line"></i>
                    Download Laporan
                </button>
            </div>

            <div class="min-h-[200px] hidden" id="reportsContent">
                <div class="text-center py-16 px-8 text-gray-400 text-lg">
                    <p>Pilih filter dan klik "Tampilkan" untuk melihat laporan</p>
                </div>
            </div>

            {{-- Tabel Laporan Realtime --}}
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hidden mb-6" id="reportsTableContainer">
                <div class="w-full overflow-hidden">
                    <table id="reportsTable" class="w-full border-collapse" style="border: 1px solid #d1d5db; table-layout: fixed;">
                        <thead>
                            <tr>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 5%;">No</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 12%;">Waktu Pesan</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 12%;">Waktu Selesai</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 12%;">Nama Pemesan</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 18%;">Pesanan</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 8%;">No Meja</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 8%;">Ruangan</th>
                                <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 10%;">Metode</th>
                                <th class="px-2 py-2 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300 bg-gray-100" style="background-color: #f3f4f6; border: 1px solid #d1d5db; width: 15%;">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody id="reportsTableBody" class="bg-white">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
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

        // Menu Navigation
        const menuOrders = document.getElementById('menu-orders');
        const menuReports = document.getElementById('menu-reports');
        const ordersSection = document.getElementById('ordersSection');
        const reportsSection = document.getElementById('reportsSection');

        function switchSection(section) {
            // Update menu active state
            menuOrders.classList.remove('active');
            menuReports.classList.remove('active');
            
            if (section === 'orders') {
                menuOrders.classList.add('active');
                ordersSection.classList.remove('hidden');
                reportsSection.classList.add('hidden');
            } else {
                menuReports.classList.add('active');
                ordersSection.classList.add('hidden');
                reportsSection.classList.remove('hidden');
            }

            // Close sidebar on mobile after selection
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebarOverlay.classList.remove('show');
            }
        }

        menuOrders.addEventListener('click', (e) => {
            e.preventDefault();
            switchSection('orders');
        });

        menuReports.addEventListener('click', (e) => {
            e.preventDefault();
            switchSection('reports');
        });

        // Notification and Sound Functions
        const notificationSound = document.getElementById('notificationSound');
        const notificationContainer = document.getElementById('notificationContainer');
        const notificationOverlay = document.getElementById('notificationOverlay');
        let currentOrderIds = new Set();
        let isFirstLoad = true;
        let activeNotifications = new Set();

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
            
            // Play sound
            if (notificationSound) {
                notificationSound.currentTime = 0;
                notificationSound.play().catch(err => {
                    console.log('Sound play failed:', err);
                });
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

        // Function to fetch and update orders
        async function fetchAndUpdateOrders() {
            try {
                const response = await fetch('/orders/active');
                const data = await response.json();
                
                if (!response.ok || !data.orders) return;
                
                const ordersSection = document.getElementById('ordersSection');
                if (!ordersSection) return;
                
                const newOrderIds = new Set(data.orders.map(o => o.id));
                
                // Detect new orders
                if (!isFirstLoad) {
                    data.orders.forEach(order => {
                        if (!currentOrderIds.has(order.id)) {
                            // New order detected!
                            showNotification(order);
                        }
                    });
                } else {
                    isFirstLoad = false;
                }
                
                // Update current order IDs
                currentOrderIds = newOrderIds;
                
                // Update orders display only if we're in orders section
                if (!ordersSection.classList.contains('hidden')) {
                    if (data.orders.length === 0) {
                        ordersSection.innerHTML = '<div class="text-center py-16 px-8 text-gray-400 text-lg"><p>Belum ada pesanan</p></div>';
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
            }
        }

        // Initialize order IDs on page load
        (function() {
            const initialOrders = @json($orders);
            initialOrders.forEach(order => {
                currentOrderIds.add(order.id);
            });
            
            // Start auto-refresh every 3 seconds
            setInterval(fetchAndUpdateOrders, 3000);
            
            // Initial fetch after 1 second
            setTimeout(fetchAndUpdateOrders, 1000);
        })();

        // Realtime Reports Table
        let reportsData = [];
        let currentFilterType = 'daily';
        let currentFilterDate = new Date().toISOString().split('T')[0];
        let currentFilterMonth = new Date().toISOString().slice(0, 7);
        let currentFilterYear = new Date().getFullYear();

        // Format currency
        function formatCurrency(amount) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Format payment method
        function formatPaymentMethod(method) {
            const methods = {
                'cash': 'Tunai',
                'qris': 'QRIS',
                'transfer': 'Transfer'
            };
            return methods[method] || method;
        }

        // Add order to reports table
        function addOrderToReportsTable(order) {
            // Check if order matches current filter
            const orderDate = new Date(order.created_at);
            let shouldAdd = false;

            if (currentFilterType === 'daily') {
                const filterDate = new Date(currentFilterDate);
                shouldAdd = orderDate.toDateString() === filterDate.toDateString();
            } else if (currentFilterType === 'monthly') {
                const filterMonth = new Date(currentFilterMonth + '-01');
                shouldAdd = orderDate.getFullYear() === filterMonth.getFullYear() && 
                           orderDate.getMonth() === filterMonth.getMonth();
            } else if (currentFilterType === 'yearly') {
                shouldAdd = orderDate.getFullYear() === parseInt(currentFilterYear);
            }

            if (shouldAdd) {
                reportsData.unshift(order); // Add to beginning
                renderReportsTable();
                updateReportsSummary();
            }
        }

        // Render reports table
        function renderReportsTable() {
            const tbody = document.getElementById('reportsTableBody');
            const container = document.getElementById('reportsTableContainer');
            
            if (!tbody || !container) return;

            if (reportsData.length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            tbody.innerHTML = '';

            reportsData.forEach((order, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors';
                
                const orderItemsText = order.order_items.map(item => 
                    `${item.quantity}x ${item.menu_name}`
                ).join(', ');

                // Format tanggal untuk tampilan lebih ringkas (WIB)
                const formatDate = (dateString) => {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        timeZone: 'Asia/Jakarta'
                    }).replace(',', '') + ' WIB';
                };

                row.innerHTML = `
                    <td class="px-2 py-2 text-sm text-gray-900 border border-gray-300 text-center" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${index + 1}</td>
                    <td class="px-2 py-2 text-xs text-gray-900 border border-gray-300" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${formatDate(order.created_at)}</td>
                    <td class="px-2 py-2 text-xs text-gray-900 border border-gray-300" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${formatDate(order.updated_at)}</td>
                    <td class="px-2 py-2 text-sm text-gray-900 border border-gray-300" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${order.customer_name}</td>
                    <td class="px-2 py-2 text-xs text-gray-900 border border-gray-300" style="border: 1px solid #d1d5db; background-color: #ffffff; word-break: break-word; overflow: hidden;">${orderItemsText}</td>
                    <td class="px-2 py-2 text-sm text-gray-900 border border-gray-300 text-center" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${order.table_number}</td>
                    <td class="px-2 py-2 text-sm text-gray-900 border border-gray-300 text-center" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${order.room}</td>
                    <td class="px-2 py-2 text-xs text-gray-900 border border-gray-300" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${formatPaymentMethod(order.payment_method)}</td>
                    <td class="px-2 py-2 text-sm text-gray-900 font-semibold text-right border border-gray-300" style="border: 1px solid #d1d5db; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis;">${formatCurrency(order.total_price)}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Update reports summary
        function updateReportsSummary() {
            const totalOrders = reportsData.length;
            const totalRevenue = reportsData.reduce((sum, order) => sum + parseFloat(order.total_price), 0);
            
            document.getElementById('totalOrders').textContent = totalOrders;
            document.getElementById('totalRevenue').textContent = formatCurrency(totalRevenue);
            
            const summary = document.getElementById('reportsSummary');
            if (summary) {
                summary.classList.remove('hidden');
                summary.classList.add('flex');
            }
        }

        // Load reports
        async function loadReports(type, date, month, year) {
            currentFilterType = type;
            currentFilterDate = date;
            currentFilterMonth = month;
            currentFilterYear = year;

            try {
                const params = new URLSearchParams({ type, date, month, year });
                const response = await fetch(`/reports?${params}`);
                const data = await response.json();

                reportsData = data.orders || [];
                
                // Hide reportsContent (yang mungkin menampilkan gambar)
                const reportsContent = document.getElementById('reportsContent');
                if (reportsContent) {
                    reportsContent.innerHTML = '';
                    reportsContent.classList.add('hidden');
                }
                
                // Show export Excel button
                const exportExcelContainer = document.getElementById('exportExcelContainer');
                if (exportExcelContainer) {
                    exportExcelContainer.classList.remove('hidden');
                }
                
                // Show table instead
                renderReportsTable();
                updateReportsSummary();
            } catch (error) {
                console.error('Error loading reports:', error);
            }
        }

        // Handle complete order - update reports table
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
                                    ordersSection.innerHTML = '<div class="text-center py-16 px-8 text-gray-400 text-lg"><p>Belum ada pesanan</p></div>';
                                }
                            }, 300);
                        }

                        // Add to reports table if in reports section
                        if (result.order && !reportsSection.classList.contains('hidden')) {
                            addOrderToReportsTable(result.order);
                        }
                    }
                } catch (error) {
                    console.error('Error completing order:', error);
                    alert('Terjadi kesalahan saat menyelesaikan pesanan');
                }
            }
        });

        // Handle filter buttons
        document.querySelectorAll('.filter-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab-btn').forEach(b => {
                    b.classList.remove('active:bg-[#fa9a08]', 'active:text-white');
                    b.classList.add('bg-[#2a2a2a]');
                });
                this.classList.add('active:bg-[#fa9a08]', 'active:text-white');
                this.classList.remove('bg-[#2a2a2a]');

                const type = this.getAttribute('data-filter-type');
                document.querySelectorAll('.filter-input-group').forEach(group => {
                    group.classList.add('hidden');
                });

                if (type === 'daily') {
                    document.getElementById('dailyFilter').classList.remove('hidden');
                } else if (type === 'monthly') {
                    document.getElementById('monthlyFilter').classList.remove('hidden');
                } else if (type === 'yearly') {
                    document.getElementById('yearlyFilter').classList.remove('hidden');
                }
            });
        });

        // Handle load reports buttons
        document.querySelectorAll('.load-reports-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                let date = currentFilterDate;
                let month = currentFilterMonth;
                let year = currentFilterYear;

                if (type === 'daily') {
                    date = document.getElementById('dailyDate').value;
                } else if (type === 'monthly') {
                    month = document.getElementById('monthlyDate').value;
                } else if (type === 'yearly') {
                    year = document.getElementById('yearlyDate').value;
                }

                loadReports(type, date, month, year);
            });
        });

        // Handle Kirim Laporan dengan popup email
        document.getElementById('sendReportBtn')?.addEventListener('click', async function() {
            // Tampilkan popup untuk input email
            const { value: email } = await Swal.fire({
                title: 'Kirim Laporan ke Email',
                html: `
                    <div style="text-align: left;">
                        <label style="display: block; margin-bottom: 8px; color: #fff; font-weight: 500;">Masukkan Email Tujuan:</label>
                        <input 
                            id="swal-email-input" 
                            type="email" 
                            placeholder="contoh@email.com" 
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: #fff; font-size: 14px;"
                            autofocus
                        >
                        <p style="margin-top: 12px; color: #999; font-size: 12px;">File Excel akan dikirim sebagai attachment ke email yang Anda masukkan.</p>
                    </div>
                `,
                background: '#161616',
                color: '#fff',
                showCancelButton: true,
                confirmButtonText: 'Kirim Email',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#fa9a08',
                cancelButtonColor: '#666',
                customClass: {
                    popup: 'swal2-popup-custom',
                    title: 'swal2-title-custom',
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-confirm-custom'
                },
                didOpen: () => {
                    const input = document.getElementById('swal-email-input');
                    input?.focus();
                },
                preConfirm: () => {
                    const email = document.getElementById('swal-email-input')?.value;
                    if (!email) {
                        Swal.showValidationMessage('Email tidak boleh kosong');
                        return false;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        Swal.showValidationMessage('Format email tidak valid');
                        return false;
                    }
                    return email;
                }
            });

            if (email) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Mengirim Email...',
                    html: '<p style="color: #999; font-size: 14px;">Sedang mengirim laporan Excel ke ' + email + '</p>',
                    background: '#161616',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    // Kirim request ke endpoint
                    const response = await fetch('/reports/send-email', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            email: email,
                            type: currentFilterType,
                            date: currentFilterDate,
                            month: currentFilterMonth,
                            year: currentFilterYear
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        let tipsHtml = '';
                        if (result.tips && result.tips.length > 0) {
                            tipsHtml = '<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);"><p style="color: #999; font-size: 12px; margin-bottom: 8px;"><strong>Tips:</strong></p><ul style="color: #999; font-size: 11px; text-align: left; padding-left: 20px;">';
                            result.tips.forEach(tip => {
                                tipsHtml += '<li style="margin-bottom: 5px;">' + tip + '</li>';
                            });
                            tipsHtml += '</ul></div>';
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: '<span class="text-white font-bold">EMAIL TERKIRIM</span>',
                            html: '<p class="text-gray-400 text-sm">' + result.message + '</p>' + tipsHtml,
                            background: '#161616',
                            confirmButtonColor: '#fa9a08',
                            customClass: {
                                popup: 'swal2-popup-custom',
                                title: 'swal2-title-custom',
                                confirmButton: 'swal2-confirm-custom'
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '<span class="text-white font-bold">GAGAL MENGIRIM</span>',
                            html: '<p class="text-red-400 text-sm">' + (result.message || 'Terjadi kesalahan saat mengirim email') + '</p>',
                            background: '#161616',
                            confirmButtonColor: '#ef4444',
                            customClass: {
                                popup: 'swal2-popup-custom',
                                title: 'swal2-title-custom',
                                confirmButton: 'swal2-confirm-custom'
                            }
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span class="text-white font-bold">ERROR</span>',
                        html: '<p class="text-red-400 text-sm">Terjadi kesalahan: ' + error.message + '</p>',
                        background: '#161616',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom',
                            confirmButton: 'swal2-confirm-custom'
                        }
                    });
                }
            }
        });

        // Handle Download Laporan - langsung download Excel
        document.getElementById('downloadReportBtn')?.addEventListener('click', function() {
            const params = new URLSearchParams({
                type: currentFilterType,
                date: currentFilterDate,
                month: currentFilterMonth,
                year: currentFilterYear
            });

            // Langsung download file Excel
            window.location.href = `/reports/export?${params}`;
        });
    </script>
    @endpush
@endsection

