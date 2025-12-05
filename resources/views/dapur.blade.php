@extends('layouts.app')

@section('title', 'Dapur - Billiard Class')

@section('content')
    <div class="dapur-container">
        <div class="dapur-header">
            <div class="dapur-tabs">
                <button class="tab-btn active" data-tab="orders">Orderan</button>
                <button class="tab-btn" data-tab="reports">Laporan</button>
            </div>
        </div>

        <div class="orders-section" id="ordersSection">
            @if($orders->count() > 0)
                <div class="orders-grid">
                    @foreach($orders as $order)
                        <div class="order-card" data-order-id="{{ $order->id }}">
                            <div class="order-images">
                                @foreach($order->orderItems as $item)
                                    <img src="{{ $item->image ? asset($item->image) : asset('assets/img/default.png') }}" 
                                         alt="{{ $item->menu_name }}" 
                                         class="order-item-image-small"
                                         onerror="this.src='{{ asset('assets/img/default.png') }}'">
                                @endforeach
                            </div>
                            <div class="order-details">
                                <p><strong>Waktu Pesan :</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</p>
                                <p><strong>Nama Pemesan :</strong> {{ $order->customer_name }}</p>
                                <p><strong>Pesanan :</strong> 
                                    {{ $order->orderItems->map(function($item){ return $item->quantity . 'x ' . $item->menu_name; })->implode(', ') }}
                                </p>
                                <p><strong>Nomor Meja :</strong> {{ $order->table_number }}</p>
                                <p><strong>Ruangan :</strong> {{ $order->room }}</p>
                                <p><strong>Total Harga :</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <button class="complete-btn" data-order-id="{{ $order->id }}">Selesai</button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>Belum ada pesanan</p>
                </div>
            @endif
        </div>

        <div class="reports-section" id="reportsSection" style="display: none;">
            <div class="reports-filter">
                <div class="filter-tabs">
                    <button class="filter-tab-btn active" data-filter-type="daily">Harian</button>
                    <button class="filter-tab-btn" data-filter-type="monthly">Bulanan</button>
                    <button class="filter-tab-btn" data-filter-type="yearly">Tahunan</button>
                </div>
                
                <div class="filter-inputs">
                    <div class="filter-input-group" id="dailyFilter">
                        <label for="dailyDate">Pilih Tanggal:</label>
                        <input type="date" id="dailyDate" class="filter-input" value="{{ date('Y-m-d') }}">
                        <button class="load-reports-btn" data-type="daily">Tampilkan</button>
                    </div>
                    
                    <div class="filter-input-group" id="monthlyFilter" style="display: none;">
                        <label for="monthlyDate">Pilih Bulan:</label>
                        <input type="month" id="monthlyDate" class="filter-input" value="{{ date('Y-m') }}">
                        <button class="load-reports-btn" data-type="monthly">Tampilkan</button>
                    </div>
                    
                    <div class="filter-input-group" id="yearlyFilter" style="display: none;">
                        <label for="yearlyDate">Pilih Tahun:</label>
                        <input type="number" id="yearlyDate" class="filter-input" min="2020" max="2099" value="{{ date('Y') }}">
                        <button class="load-reports-btn" data-type="yearly">Tampilkan</button>
                    </div>
                </div>
            </div>

            <div class="reports-summary" id="reportsSummary" style="display: none;">
                <div class="summary-card">
                    <h3>Total Pesanan</h3>
                    <p id="totalOrders">0</p>
                </div>
                <div class="summary-card">
                    <h3>Total Pendapatan</h3>
                    <p id="totalRevenue">Rp0</p>
                </div>
                <button class="export-excel-btn" id="exportExcelBtn">Export Excel</button>
            </div>

            <div class="reports-content" id="reportsContent">
                <div class="empty-state">
                    <p>Pilih filter dan klik "Tampilkan" untuk melihat laporan</p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dapur.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('js/dapur.js') }}"></script>
    @endpush
@endsection

