@extends('layouts.app')

@section('title', 'Laporan - Billiard Class')

@push('head')
    {{-- Initialize theme immediately in head --}}
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch(e) {
                console.error('Theme initialization error:', e);
            }
        })();
    </script>
@endpush

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
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 260px;
        }

        .main-content {
            padding: 0.75rem;
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
    }
</style>
@endpush

@section('content')
    {{-- Initialize theme before Alpine.js loads --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

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
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-200 hidden lg:block">Laporan</h2>
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
                {{-- Grafik Laporan Order per Kategori --}}
                <div class="bg-white dark:bg-[#0A0A0A] border border-gray-200 dark:border-white/10 p-6 rounded-2xl mb-6 shadow-sm dark:shadow-none">
                    <h2 class="text-xl font-bold text-white dark:text-slate-200 mb-6 flex items-center gap-2">
                        <i class="ri-line-chart-line text-[#fa9a08]"></i>
                        Laporan Order per Kategori
                    </h2>
                    
                    {{-- Filter Section --}}
                    <div class="bg-gray-50 dark:bg-[#050505] rounded-xl p-6 mb-6 border border-gray-200 dark:border-white/10">
                        <div class="flex gap-2 mb-4">
                            <button class="filter-tab-btn py-2 px-4 bg-[#fa9a08] border-none text-white text-sm font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#e19e2b]" data-filter-type="daily">Harian</button>
                            <button class="filter-tab-btn py-2 px-4 bg-[#2a2a2a] dark:bg-[#1a1a1a] border-none text-white dark:text-slate-200 text-sm font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#3a3a3a] dark:hover:bg-[#2a2a2a]" data-filter-type="monthly">Bulanan</button>
                            <button class="filter-tab-btn py-2 px-4 bg-[#2a2a2a] dark:bg-[#1a1a1a] border-none text-white dark:text-slate-200 text-sm font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#3a3a3a] dark:hover:bg-[#2a2a2a]" data-filter-type="yearly">Tahunan</button>
                        </div>
                        
                        <div class="flex flex-col gap-4">
                            {{-- Daily Filter --}}
                            <div class="filter-input-group flex items-center gap-4 flex-wrap" id="dailyFilter">
                                <label for="dailyDate" class="text-white dark:text-slate-200 font-medium min-w-[120px]">Pilih Tanggal:</label>
                                <input type="date" id="dailyDate" class="py-2 px-3 bg-[#2a2a2a] dark:bg-[#1a1a1a] border border-[#555] dark:border-white/10 rounded-lg text-white dark:text-slate-200 text-sm flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" value="{{ date('Y-m-d') }}">
                                <button class="load-chart-btn py-2 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="daily">Tampilkan</button>
                            </div>
                            
                            {{-- Monthly Filter --}}
                            <div class="filter-input-group items-center gap-4 flex-wrap hidden" id="monthlyFilter">
                                <label for="monthlyDate" class="text-white dark:text-slate-200 font-medium min-w-[120px]">Pilih Bulan:</label>
                                <input type="month" id="monthlyDate" class="py-2 px-3 bg-[#2a2a2a] dark:bg-[#1a1a1a] border border-[#555] dark:border-white/10 rounded-lg text-white dark:text-slate-200 text-sm flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" value="{{ date('Y-m') }}">
                                <button class="load-chart-btn py-2 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="monthly">Tampilkan</button>
                            </div>
                            
                            {{-- Yearly Filter --}}
                            <div class="filter-input-group items-center gap-4 flex-wrap hidden" id="yearlyFilter">
                                <label for="yearlyDate" class="text-white dark:text-slate-200 font-medium min-w-[120px]">Pilih Tahun:</label>
                                <input type="number" id="yearlyDate" class="py-2 px-3 bg-[#2a2a2a] dark:bg-[#1a1a1a] border border-[#555] dark:border-white/10 rounded-lg text-white dark:text-slate-200 text-sm flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" min="2020" max="2099" value="{{ date('Y') }}">
                                <button class="load-chart-btn py-2 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="yearly">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Chart Container --}}
                    <div class="bg-gray-50 dark:bg-[#050505] rounded-xl p-6 mb-6 border border-gray-200 dark:border-white/10">
                        <canvas id="categoryChart" style="max-height: 400px;"></canvas>
                    </div>
                    
                    {{-- Detail Orderan per Kategori --}}
                    <div class="bg-gray-50 dark:bg-[#050505] rounded-xl p-6 mb-6 border border-gray-200 dark:border-white/10" id="categoryDetails">
                        <h3 class="text-lg font-bold text-white dark:text-slate-200 mb-4">Detail Orderan</h3>
                        <div id="categoryDetailsContent" class="space-y-4">
                            <!-- Detail akan diisi via JavaScript -->
                        </div>
                    </div>
                    
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" id="categorySummary">
                        <!-- Summary cards will be generated dynamically -->
                    </div>
                    
                    {{-- Total Gabungan Card --}}
                    <div class="bg-gradient-to-r from-[#fa9a08]/20 to-[#fa9a08]/10 rounded-xl p-6 border-2 border-[#fa9a08]/30" id="totalSummaryCard">
                        <!-- Total gabungan akan diisi via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
                    sidebar.classList.remove('collapsed');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.add('show');
                    }
                    document.body.style.overflow = 'hidden';
                } else {
                    sidebar.classList.add('collapsed');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('show');
                    }
                    document.body.style.overflow = '';
                }
            }
        }

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

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('collapsed');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('show');
                }
                document.body.style.overflow = '';
            } else {
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                }
            }
        });

        // Category Chart untuk Laporan
        let categoryChart = null;
        let currentFilterType = 'daily';
        let currentFilterDate = new Date().toISOString().split('T')[0];
        let currentFilterMonth = new Date().toISOString().slice(0, 7);
        let currentFilterYear = new Date().getFullYear();

        // Load category statistics and render chart
        async function loadCategoryChart(type, date, month, year) {
            try {
                type = type || currentFilterType;
                date = date || currentFilterDate;
                month = month || currentFilterMonth;
                year = year || currentFilterYear;

                currentFilterType = type;
                currentFilterDate = date;
                currentFilterMonth = month;
                currentFilterYear = year;

                const params = new URLSearchParams({ type });
                if (type === 'daily') {
                    params.append('date', date);
                } else if (type === 'monthly') {
                    params.append('month', month);
                } else if (type === 'yearly') {
                    params.append('year', year);
                }

                const response = await fetch(`/reports/category-stats?${params}`);
                const data = await response.json();

                if (!data.success || !data.data) {
                    console.error('Error loading category stats:', data.message);
                    return;
                }

                const categoryData = data.data;
                
                renderCategoryChart(categoryData);
                renderCategoryDetails(categoryData);
                renderCategorySummary(categoryData);
            } catch (error) {
                console.error('Error loading category chart:', error);
            }
        }

        // Render category chart using Chart.js
        function renderCategoryChart(data) {
            const ctx = document.getElementById('categoryChart');
            if (!ctx) return;

            if (categoryChart) {
                categoryChart.destroy();
            }

            const labels = data.map(item => item.name);
            const quantities = data.map(item => item.total_quantity);
            const revenues = data.map(item => item.total_revenue);

            categoryChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Quantity',
                            data: quantities,
                            backgroundColor: 'rgba(250, 154, 8, 0.1)',
                            borderColor: 'rgba(250, 154, 8, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgba(250, 154, 8, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointStyle: 'circle',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Total Revenue (Rp)',
                            data: revenues,
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointStyle: 'circle',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#fff',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#fa9a08',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 0) {
                                        label += context.parsed.y + ' item';
                                    } else {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#fff',
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                color: '#fa9a08',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return value + ' item';
                                }
                            },
                            grid: {
                                color: 'rgba(250, 154, 8, 0.2)'
                            },
                            title: {
                                display: true,
                                text: 'Total Quantity',
                                color: '#fa9a08',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            ticks: {
                                color: '#3b82f6',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                            title: {
                                display: true,
                                text: 'Total Revenue',
                                color: '#3b82f6',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render category details (keterangan orderan)
        function renderCategoryDetails(data) {
            const container = document.getElementById('categoryDetailsContent');
            if (!container) return;

            container.innerHTML = '';

            data.forEach(category => {
                if (category.items && category.items.length > 0) {
                    const detailCard = document.createElement('div');
                    detailCard.className = 'bg-[#2a2a2a] dark:bg-[#1a1a1a] rounded-lg p-4 border border-white/10';
                    
                    const itemsText = category.items.map(item => 
                        `${item.quantity}x ${item.name}`
                    ).join(', ');
                    
                    detailCard.innerHTML = `
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[#fa9a08]/20 flex items-center justify-center mt-1">
                                <i class="ri-restaurant-line text-[#fa9a08]"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white dark:text-slate-200 font-bold mb-1">${category.name}</h4>
                                <p class="text-gray-300 dark:text-gray-400 text-sm">${itemsText}</p>
                            </div>
                        </div>
                    `;
                    container.appendChild(detailCard);
                }
            });

            const detailsContainer = document.getElementById('categoryDetails');
            if (detailsContainer && container.children.length > 0) {
                detailsContainer.classList.remove('hidden');
            } else if (detailsContainer) {
                detailsContainer.classList.add('hidden');
            }
        }

        // Render category summary cards
        function renderCategorySummary(data) {
            const container = document.getElementById('categorySummary');
            if (!container) return;

            container.innerHTML = '';

            data.forEach(category => {
                const card = document.createElement('div');
                card.className = 'bg-[#2a2a2a] dark:bg-[#1a1a1a] rounded-xl p-6 border border-white/10';
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white dark:text-slate-200 text-lg font-bold">${category.name}</h3>
                        <div class="w-12 h-12 rounded-lg bg-[#fa9a08]/20 flex items-center justify-center">
                            <i class="ri-bar-chart-box-line text-2xl text-[#fa9a08]"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mb-1">Total Quantity</p>
                            <p class="text-white dark:text-slate-200 text-2xl font-bold">${category.total_quantity}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mb-1">Total Revenue</p>
                            <p class="text-white dark:text-slate-200 text-xl font-bold">Rp ${new Intl.NumberFormat('id-ID').format(category.total_revenue)}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mb-1">Total Order</p>
                            <p class="text-white dark:text-slate-200 text-lg font-semibold">${category.order_count} order</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });

            renderTotalSummary(data);
        }

        // Render total gabungan seluruh menu
        function renderTotalSummary(data) {
            const container = document.getElementById('totalSummaryCard');
            if (!container) return;

            let totalQuantity = 0;
            let totalRevenue = 0;

            data.forEach(category => {
                totalQuantity += category.total_quantity;
                totalRevenue += category.total_revenue;
            });

            let maxOrderCount = Math.max(...data.map(cat => cat.order_count), 0);

            container.innerHTML = `
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-white text-2xl font-bold flex items-center gap-3">
                        <i class="ri-stack-line text-[#fa9a08]"></i>
                        Total Gabungan Seluruh Menu
                    </h3>
                    <div class="w-16 h-16 rounded-xl bg-[#fa9a08]/30 flex items-center justify-center">
                        <i class="ri-dashboard-3-line text-3xl text-[#fa9a08]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-[#0f0f0f]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-400 text-sm mb-2">Total Quantity</p>
                        <p class="text-white text-3xl font-bold text-[#fa9a08]">${totalQuantity}</p>
                        <p class="text-gray-500 text-xs mt-1">item</p>
                    </div>
                    <div class="bg-[#0f0f0f]/50 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-400 dark:text-gray-500 text-sm mb-2">Total Revenue</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">Rp ${new Intl.NumberFormat('id-ID').format(totalRevenue)}</p>
                        <p class="text-gray-500 dark:text-gray-600 text-xs mt-1">pendapatan</p>
                    </div>
                    <div class="bg-[#0f0f0f]/50 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-400 dark:text-gray-500 text-sm mb-2">Total Order</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">${maxOrderCount}</p>
                        <p class="text-gray-500 dark:text-gray-600 text-xs mt-1">order</p>
                    </div>
                </div>
            `;
        }

        // Load chart on page load
        window.addEventListener('DOMContentLoaded', () => {
            loadCategoryChart();
        });

        // Handle filter tab buttons
        document.querySelectorAll('.filter-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab-btn').forEach(b => {
                    b.classList.remove('bg-[#fa9a08]');
                    b.classList.add('bg-[#2a2a2a]');
                });
                this.classList.add('bg-[#fa9a08]');
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

        // Handle load chart buttons
        document.querySelectorAll('.load-chart-btn').forEach(btn => {
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

                loadCategoryChart(type, date, month, year);
            });
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
                    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        this.darkMode = true;
                        document.documentElement.classList.add('dark');
                    } else {
                        this.darkMode = false;
                        document.documentElement.classList.remove('dark');
                    }
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

