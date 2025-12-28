@extends('layouts.app')

@section('title', 'Laporan - Billiard Class')

{{-- Include shift calculation PHP block --}}
@include('dapur.partials.shift-calculation')

{{-- Include shift meta tags --}}
@include('dapur.partials.shift-meta')

{{-- Include theme initialization script --}}
@include('dapur.partials.theme-manager')

{{-- Include common styles --}}
@include('dapur.partials.common-styles')

{{-- Include sidebar & main content styles --}}
@include('dapur.partials.sidebar-main-styles')

@push('styles')
<style>
    /* Filter tabs mobile */
    @media (max-width: 768px) {
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
    {{-- Logout Form --}}
    @include('dapur.partials.logout-form')

    <div class="flex min-h-screen bg-gray-50 dark:bg-[#050505] theme-transition text-black dark:text-slate-200" x-data="themeManager()" x-init="initTheme()">
        {{-- Sidebar --}}
        @include('dapur.partials.sidebar')

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full" :class="sidebarCollapsed ? 'desktop-collapsed' : ''">
            {{-- Navbar --}}
            @include('dapur.partials.navbar', ['pageTitle' => 'Laporan'])

            <div class="flex-1 p-8 md:p-12 min-h-screen">
                {{-- Grafik Laporan Order per Kategori --}}
                <div class="bg-gray-800 dark:bg-[#0A0A0A] border border-gray-700 dark:border-white/10 p-6 rounded-2xl mb-6 shadow-sm dark:shadow-none">
                    <h2 class="text-xl font-bold text-white dark:text-slate-200 mb-6 flex items-center gap-2">
                        <i class="ri-line-chart-line text-[#fa9a08]"></i>
                        Laporan Order per Kategori
                    </h2>
                    
                    {{-- Filter Section --}}
                    <div class="bg-gray-700 dark:bg-[#050505] rounded-xl p-6 mb-6 border border-gray-600 dark:border-white/10">
                        <div class="flex gap-2 mb-4">
                            <button class="filter-tab-btn py-2 px-4 bg-[#fa9a08] border-none text-white text-sm font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-[#e19e2b]" data-filter-type="daily">Harian</button>
                            <button class="filter-tab-btn py-2 px-4 bg-gray-700 dark:bg-[#1a1a1a] border-none text-white dark:text-slate-200 text-sm font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-gray-600 dark:hover:bg-[#2a2a2a]" data-filter-type="monthly">Bulanan</button>
                            <button class="filter-tab-btn py-2 px-4 bg-gray-700 dark:bg-[#1a1a1a] border-none text-white dark:text-slate-200 text-sm font-medium cursor-pointer rounded-lg transition-all duration-200 hover:bg-gray-600 dark:hover:bg-[#2a2a2a]" data-filter-type="yearly">Tahunan</button>
                        </div>
                        
                        <div class="flex flex-col gap-4">
                            {{-- Daily Filter --}}
                            <div class="filter-input-group flex items-center gap-4 flex-wrap" id="dailyFilter">
                                <label for="dailyDate" class="text-white dark:text-slate-200 font-medium min-w-[120px]">Pilih Tanggal:</label>
                                <input type="date" id="dailyDate" class="py-2 px-3 bg-gray-700 dark:bg-[#1a1a1a] border border-gray-600 dark:border-white/10 rounded-lg text-white dark:text-slate-200 text-sm flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" value="{{ date('Y-m-d') }}">
                                <button class="load-chart-btn py-2 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="daily">Tampilkan</button>
                            </div>
                            
                            {{-- Monthly Filter --}}
                            <div class="filter-input-group items-center gap-4 flex-wrap hidden" id="monthlyFilter">
                                <label for="monthlyDate" class="text-white dark:text-slate-200 font-medium min-w-[120px]">Pilih Bulan:</label>
                                <input type="month" id="monthlyDate" class="py-2 px-3 bg-gray-700 dark:bg-[#1a1a1a] border border-gray-600 dark:border-white/10 rounded-lg text-white dark:text-slate-200 text-sm flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" value="{{ date('Y-m') }}">
                                <button class="load-chart-btn py-2 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="monthly">Tampilkan</button>
                            </div>
                            
                            {{-- Yearly Filter --}}
                            <div class="filter-input-group items-center gap-4 flex-wrap hidden" id="yearlyFilter">
                                <label for="yearlyDate" class="text-white dark:text-slate-200 font-medium min-w-[120px]">Pilih Tahun:</label>
                                <input type="number" id="yearlyDate" class="py-2 px-3 bg-gray-700 dark:bg-[#1a1a1a] border border-gray-600 dark:border-white/10 rounded-lg text-white dark:text-slate-200 text-sm flex-1 max-w-[300px] focus:outline-none focus:border-[#fa9a08]" min="2020" max="2099" value="{{ date('Y') }}">
                                <button class="load-chart-btn py-2 px-6 bg-[#fa9a08] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 hover:bg-[#e19e2b]" data-type="yearly">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Chart Container --}}
                    <div class="bg-gray-700 dark:bg-[#050505] rounded-xl p-6 mb-6 border border-gray-600 dark:border-white/10">
                        <canvas id="categoryChart" style="max-height: 400px;"></canvas>
                    </div>
                    
                    {{-- Detail Orderan per Kategori --}}
                    <div class="bg-gray-700 dark:bg-[#050505] rounded-xl p-6 mb-6 border border-gray-600 dark:border-white/10" id="categoryDetails">
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
                const chartType = data.type || 'daily';
                
                renderCategoryChart(categoryData, chartType);
                
                // Hanya render detail dan summary untuk daily (per kategori)
                if (chartType === 'daily') {
                    renderCategoryDetails(categoryData);
                    renderCategorySummary(categoryData);
                } else {
                    // Untuk monthly dan yearly, render summary periode
                    renderPeriodSummary(categoryData, chartType);
                }
            } catch (error) {
                console.error('Error loading category chart:', error);
            }
        }

        // Render category chart using Chart.js
        function renderCategoryChart(data, type = 'daily') {
            const ctx = document.getElementById('categoryChart');
            if (!ctx) return;

            if (categoryChart) {
                categoryChart.destroy();
            }

            const labels = data.map(item => item.name || item.period);
            const quantities = data.map(item => item.total_quantity);
            const revenues = data.map(item => item.total_revenue);
            
            // Tentukan label untuk dataset berdasarkan type
            let quantityLabel = 'Total Quantity';
            let revenueLabel = 'Total Revenue (Rp)';
            
            if (type === 'monthly') {
                quantityLabel = 'Quantity per Minggu';
                revenueLabel = 'Revenue per Minggu (Rp)';
            } else if (type === 'yearly') {
                quantityLabel = 'Quantity per Bulan';
                revenueLabel = 'Revenue per Bulan (Rp)';
            }

            categoryChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: quantityLabel,
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
                            label: revenueLabel,
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
                        title: {
                            display: true,
                            text: type === 'monthly' ? 'Grafik Pendapatan per Minggu' : (type === 'yearly' ? 'Grafik Pendapatan per Bulan' : 'Laporan Order per Kategori'),
                            color: '#fff',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
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
                detailCard.className = 'bg-gray-700 dark:bg-[#1a1a1a] rounded-lg p-4 border border-gray-600 dark:border-white/10';
                    
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
                card.className = 'bg-gray-700 dark:bg-[#1a1a1a] rounded-xl p-6 border border-gray-600 dark:border-white/10';
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

        // Render period summary for monthly and yearly
        function renderPeriodSummary(data, type) {
            const container = document.getElementById('categorySummary');
            if (!container) return;

            container.innerHTML = '';

            // Hide category details for monthly/yearly
            const detailsContainer = document.getElementById('categoryDetails');
            if (detailsContainer) {
                detailsContainer.classList.add('hidden');
            }

            data.forEach(period => {
                const card = document.createElement('div');
                card.className = 'bg-gray-700 dark:bg-[#1a1a1a] rounded-xl p-6 border border-gray-600 dark:border-white/10';
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white dark:text-slate-200 text-lg font-bold">${period.name || period.period}</h3>
                        <div class="w-12 h-12 rounded-lg bg-[#fa9a08]/20 flex items-center justify-center">
                            <i class="ri-calendar-line text-2xl text-[#fa9a08]"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mb-1">Total Quantity</p>
                            <p class="text-white dark:text-slate-200 text-2xl font-bold">${period.total_quantity}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mb-1">Total Revenue</p>
                            <p class="text-white dark:text-slate-200 text-xl font-bold">Rp ${new Intl.NumberFormat('id-ID').format(period.total_revenue)}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mb-1">Total Order</p>
                            <p class="text-white dark:text-slate-200 text-lg font-semibold">${period.order_count} order</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });

            // Render total summary untuk periode
            renderPeriodTotalSummary(data, type);
        }

        // Render total summary untuk periode (monthly/yearly)
        function renderPeriodTotalSummary(data, type) {
            const container = document.getElementById('totalSummaryCard');
            if (!container) return;

            let totalQuantity = 0;
            let totalRevenue = 0;
            let totalOrders = 0;

            data.forEach(period => {
                totalQuantity += period.total_quantity;
                totalRevenue += period.total_revenue;
                totalOrders += period.order_count;
            });

            const title = type === 'monthly' ? 'Total Gabungan Seluruh Minggu' : 'Total Gabungan Seluruh Bulan';

            container.innerHTML = `
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-white text-2xl font-bold flex items-center gap-3">
                        <i class="ri-stack-line text-[#fa9a08]"></i>
                        ${title}
                    </h3>
                    <div class="w-16 h-16 rounded-xl bg-[#fa9a08]/30 flex items-center justify-center">
                        <i class="ri-dashboard-3-line text-3xl text-[#fa9a08]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-700 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-300 dark:text-gray-500 text-sm mb-2">Total Quantity</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">${totalQuantity}</p>
                        <p class="text-gray-400 dark:text-gray-400 text-xs mt-1">item</p>
                    </div>
                    <div class="bg-gray-700 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-300 dark:text-gray-500 text-sm mb-2">Total Revenue</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">Rp ${new Intl.NumberFormat('id-ID').format(totalRevenue)}</p>
                        <p class="text-gray-400 dark:text-gray-600 text-xs mt-1">pendapatan</p>
                    </div>
                    <div class="bg-gray-700 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-300 dark:text-gray-500 text-sm mb-2">Total Order</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">${totalOrders}</p>
                        <p class="text-gray-400 dark:text-gray-400 text-xs mt-1">order</p>
                    </div>
                </div>
            `;
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
                    <div class="bg-gray-700 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-300 dark:text-gray-500 text-sm mb-2">Total Quantity</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">${totalQuantity}</p>
                        <p class="text-gray-400 dark:text-gray-400 text-xs mt-1">item</p>
                    </div>
                    <div class="bg-gray-700 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-300 dark:text-gray-500 text-sm mb-2">Total Revenue</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">Rp ${new Intl.NumberFormat('id-ID').format(totalRevenue)}</p>
                        <p class="text-gray-400 dark:text-gray-600 text-xs mt-1">pendapatan</p>
                    </div>
                    <div class="bg-gray-700 dark:bg-[#050505]/50 rounded-lg p-4 border border-[#fa9a08]/20">
                        <p class="text-gray-300 dark:text-gray-500 text-sm mb-2">Total Order</p>
                        <p class="text-white dark:text-slate-200 text-3xl font-bold text-[#fa9a08]">${maxOrderCount}</p>
                        <p class="text-gray-400 dark:text-gray-400 text-xs mt-1">order</p>
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
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const type = this.getAttribute('data-filter-type');
                
                // Reset all buttons
                document.querySelectorAll('.filter-tab-btn').forEach(b => {
                    b.classList.remove('bg-[#fa9a08]');
                    b.classList.remove('hover:bg-[#e19e2b]');
                    b.classList.add('bg-gray-700');
                    b.classList.add('dark:bg-[#1a1a1a]');
                });
                
                // Activate clicked button
                this.classList.remove('bg-gray-700');
                this.classList.remove('dark:bg-[#1a1a1a]');
                this.classList.add('bg-[#fa9a08]');
                this.classList.add('hover:bg-[#e19e2b]');
                
                // Hide all filter groups
                document.querySelectorAll('.filter-input-group').forEach(group => {
                    group.classList.add('hidden');
                });

                // Show selected filter group
                if (type === 'daily') {
                    const dailyFilter = document.getElementById('dailyFilter');
                    if (dailyFilter) {
                        dailyFilter.classList.remove('hidden');
                    }
                } else if (type === 'monthly') {
                    const monthlyFilter = document.getElementById('monthlyFilter');
                    if (monthlyFilter) {
                        monthlyFilter.classList.remove('hidden');
                    }
                } else if (type === 'yearly') {
                    const yearlyFilter = document.getElementById('yearlyFilter');
                    if (yearlyFilter) {
                        yearlyFilter.classList.remove('hidden');
                    }
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

    {{-- Include theme manager script --}}
    @include('dapur.partials.theme-manager')
    
    {{-- Include shift check script --}}
    @include('dapur.partials.shift-check-script')
    
    @endpush
@endsection

