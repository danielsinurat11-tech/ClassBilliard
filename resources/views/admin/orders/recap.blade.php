@extends('layouts.admin')

@section('title', 'Tutup Hari')

@push('styles')
<style>
    .recap-card {
        background: linear-gradient(135deg, rgba(30, 30, 30, 0.9) 0%, rgba(20, 20, 20, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .recap-card:hover {
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .stat-card {
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 0.75rem;
        padding: 1rem;
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pagination > * {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination a,
    .pagination span {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 2.5rem;
        text-align: center;
    }

    .pagination a {
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: #a78bfa;
        text-decoration: none;
    }

    .pagination a:hover {
        background: rgba(139, 92, 246, 0.3);
        border-color: rgba(139, 92, 246, 0.5);
        color: #c4b5fd;
        transform: translateY(-1px);
    }

    .pagination span {
        background: rgba(139, 92, 246, 0.5);
        border: 1px solid rgba(139, 92, 246, 0.7);
        color: white;
        font-weight: 600;
    }

    .pagination .disabled span {
        background: rgba(107, 114, 128, 0.2);
        border-color: rgba(107, 114, 128, 0.3);
        color: rgba(156, 163, 175, 0.5);
        cursor: not-allowed;
    }

    /* Receipt/Nota Styles */
    .receipt-container {
        max-width: 400px;
        margin: 0 auto 2rem;
        background: white;
        border: none;
        padding: 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .receipt-content {
        padding: 20px;
        color: #000;
        font-family: 'Courier New', monospace;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 15px;
    }

    .receipt-title {
        font-size: 24px;
        font-weight: bold;
        margin: 0 0 5px 0;
        color: #000;
    }

    .receipt-address {
        font-size: 12px;
        margin: 5px 0;
        color: #000;
    }

    .receipt-id {
        font-size: 11px;
        margin: 5px 0;
        color: #000;
        letter-spacing: 1px;
    }

    .receipt-divider {
        border-top: 1px dashed #000;
        margin: 15px 0;
    }

    .receipt-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 12px;
    }

    .receipt-info-left {
        text-align: left;
    }

    .receipt-info-right {
        text-align: right;
    }

    .receipt-date,
    .receipt-time,
    .receipt-number,
    .receipt-staff {
        margin: 2px 0;
        color: #000;
    }

    .receipt-items {
        margin-bottom: 15px;
    }

    .receipt-item {
        margin-bottom: 10px;
        font-size: 12px;
    }

    .receipt-item-name {
        margin-bottom: 3px;
        color: #000;
    }

    .receipt-item-details {
        display: flex;
        justify-content: space-between;
        color: #000;
    }

    .receipt-summary {
        margin-bottom: 15px;
    }

    .receipt-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 12px;
        color: #000;
    }

    .receipt-footer {
        text-align: center;
        font-size: 11px;
        margin-top: 15px;
        color: #000;
    }

    .receipt-footer p {
        margin: 3px 0;
    }

    .receipt-actions {
        display: flex;
        gap: 10px;
        padding: 15px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
    }

    .btn-print,
    .btn-download,
    .btn-email {
        flex: 1;
        min-width: 120px;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-print {
        background: #22c55e;
        color: white;
    }

    .btn-print:hover {
        background: #16a34a;
    }

    .btn-download {
        background: #3b82f6;
        color: white;
    }

    .btn-download:hover {
        background: #2563eb;
    }

    .btn-email {
        background: #8b5cf6;
        color: white;
    }

    .btn-email:hover {
        background: #7c3aed;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .print-container, .print-container * {
            visibility: visible;
        }
        .print-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 400px;
            background: white;
            color: black;
            padding: 0;
            border: none;
        }
        .no-print {
            display: none !important;
        }
        .receipt-container {
            page-break-after: always;
            margin: 0;
            box-shadow: none;
        }
        .receipt-container:last-child {
            page-break-after: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Tutup Hari</h1>
            <p class="text-gray-500 text-sm">Histori tutup hari yang sudah dibuat. Order dapat di-rekap langsung dari halaman Manajemen Order.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl transition-all flex items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Kembali ke Manajemen Order
        </a>
    </div>

    {{-- Filter Tanggal --}}
    <div class="recap-card">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <i class="ri-filter-line text-purple-400"></i>
            Filter Tanggal
        </h2>
        <form method="GET" action="{{ route('admin.orders.recap.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-400 mb-2">Pilih Tanggal</label>
                <input type="date" name="filter_date" value="{{ request('filter_date', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d')) }}"
                    class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50 focus:border-purple-600 transition-all">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
                    <i class="ri-search-line"></i>
                    Filter
                </button>
                @if(request('filter_date'))
                <a href="{{ route('admin.orders.recap.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
                    <i class="ri-close-line"></i>
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Daftar Tutup Hari --}}
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="ri-history-line text-purple-400"></i>
                Histori Tutup Hari
                @if(request('filter_date'))
                <span class="text-sm font-normal text-gray-400 ml-2">
                    ({{ \Carbon\Carbon::parse(request('filter_date'))->setTimezone('Asia/Jakarta')->format('d M Y') }})
                </span>
                @endif
            </h2>
            @if(!$reports->isEmpty())
            <div class="text-sm text-gray-400">
                Menampilkan {{ $reports->firstItem() ?? 0 }}-{{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() }} rekap
            </div>
            @endif
        </div>

        @if($reports->isEmpty())
        <div class="recap-card text-center py-12">
            <i class="ri-file-list-3-line text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400">Belum ada tutup hari yang dibuat.</p>
        </div>
        @else
        @foreach($reports as $report)
        <div class="receipt-container print-container" id="recap-{{ $report->id }}">
            <div class="receipt-content">
                {{-- Header --}}
                <div class="receipt-header">
                    <h2 class="receipt-title">BILLIARD CLASS</h2>
                    <p class="receipt-address">Jl. Alpukat, Madurejo, Kec. Arut Sel., Kabupaten Kotawaringin Barat, Kalimantan Tengah 74117</p>
                    <p class="receipt-id">{{ str_pad($report->id, 20, '0', STR_PAD_LEFT) }}</p>
                </div>

                <div class="receipt-divider"></div>

                {{-- Transaction Info --}}
                <div class="receipt-info">
                    <div class="receipt-info-left">
                        <p class="receipt-date">{{ \Carbon\Carbon::parse($report->report_date)->setTimezone('Asia/Jakarta')->format('Y-m-d') }}</p>
                        <p class="receipt-time">{{ \Carbon\Carbon::parse($report->created_at)->utc()->setTimezone('Asia/Jakarta')->format('H:i:s') }}</p>
                        <p class="receipt-number">No.{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="receipt-info-right">
                        <p class="receipt-staff">{{ $report->created_by }}</p>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                {{-- Items List --}}
                <div class="receipt-items">
                    @php
                        $orderSummary = is_array($report->order_summary) ? $report->order_summary : json_decode($report->order_summary, true);
                    @endphp
                    @foreach($orderSummary as $order)
                        @foreach($order['items'] as $item)
                        <div class="receipt-item">
                            <div class="receipt-item-name">{{ $item['menu_name'] }}</div>
                            <div class="receipt-item-details">
                                <span>{{ $item['quantity'] }} x {{ number_format($item['price'], 0, ',', '.') }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="receipt-divider"></div>

                {{-- Summary --}}
                <div class="receipt-summary">
                    <div class="receipt-summary-row">
                        <span>Total:</span>
                        <span>Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</span>
                    </div>
                    @if($report->cash_revenue > 0)
                    <div class="receipt-summary-row">
                        <span>Bayar (Cash):</span>
                        <span>Rp {{ number_format($report->cash_revenue, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($report->qris_revenue > 0)
                    <div class="receipt-summary-row">
                        <span>Bayar (QRIS):</span>
                        <span>Rp {{ number_format($report->qris_revenue, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($report->transfer_revenue > 0)
                    <div class="receipt-summary-row">
                        <span>Bayar (Transfer):</span>
                        <span>Rp {{ number_format($report->transfer_revenue, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="receipt-summary-row">
                        <span>Total Order:</span>
                        <span>{{ number_format($report->total_orders) }} order</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="receipt-footer">
                    <p>Tutup Hari</p>
                    <p>Billiard Class</p>
                </div>
            </div>

            {{-- Action Buttons (Hidden saat print) --}}
            <div class="receipt-actions no-print">
                <button onclick="printRecap({{ $report->id }})" class="btn-print">
                    <i class="ri-printer-line"></i>
                    Print Nota
                </button>
                <button onclick="downloadRecap({{ $report->id }})" class="btn-download">
                    <i class="ri-download-line"></i>
                    Download Excel
                </button>
                <button onclick="sendRecapEmail({{ $report->id }})" class="btn-email">
                    <i class="ri-mail-line"></i>
                    Kirim Email
                </button>
            </div>
        </div>
        @endforeach

        {{-- Pagination --}}
        @if($reports->hasPages())
        <div class="mt-8 flex justify-center">
            <div class="bg-[#1A1A1A] border border-white/10 rounded-xl p-4">
                {{ $reports->links() }}
            </div>
        </div>
        @endif
        @endif
    </div>
</div>

{{-- Modal Update Recap --}}
<div id="updateRecapModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/10 rounded-2xl max-w-2xl w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="ri-refresh-line text-orange-400"></i>
                    Perbarui Tutup Hari
                </h3>
                <button onclick="closeUpdateRecapModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <form id="updateRecapForm" class="space-y-4">
                @csrf
                <input type="hidden" id="updateRecapId" name="report_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Mulai</label>
                        <input type="date" id="updateStartDate" name="start_date" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-orange-600/50 focus:border-orange-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Akhir</label>
                        <input type="date" id="updateEndDate" name="end_date" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-orange-600/50 focus:border-orange-600 transition-all">
                    </div>
                </div>
                <div class="bg-orange-600/10 border border-orange-600/30 rounded-xl p-4">
                    <p class="text-sm text-orange-300 flex items-start gap-2">
                        <i class="ri-information-line text-lg mt-0.5"></i>
                        <span>Tutup hari akan diperbarui dengan data terbaru. Order baru yang completed untuk periode ini akan ditambahkan ke tutup hari.</span>
                    </p>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeUpdateRecapModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="ri-refresh-line"></i>
                        Perbarui Tutup Hari
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Detail Report --}}
<div id="reportDetailModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/10 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gray-900 border-b border-white/10 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Detail Tutup Hari</h3>
                <button onclick="closeReportDetail()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            {{-- Filter Section --}}
            <div class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-400 mb-2">Filter Berdasarkan Meja</label>
                    <select id="filterTable" class="w-full bg-black/40 border border-white/10 rounded-xl py-2 px-4 text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50 focus:border-purple-600 transition-all">
                        <option value="">Semua Meja</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-400 mb-2">Filter Berdasarkan Ruangan</label>
                    <select id="filterRoom" class="w-full bg-black/40 border border-white/10 rounded-xl py-2 px-4 text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50 focus:border-purple-600 transition-all">
                        <option value="">Semua Ruangan</option>
                    </select>
                </div>
                <button onclick="resetFilter()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-xl transition-all flex items-center gap-2">
                    <i class="ri-refresh-line"></i>
                    Reset
                </button>
            </div>
            <div id="filterStats" class="mt-4 text-sm text-gray-400">
                <!-- Stats akan diisi via JavaScript -->
            </div>
        </div>
        <div id="reportDetailContent" class="p-6">
            <!-- Content akan diisi via JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


    let allOrdersData = []; // Simpan semua order data untuk filtering

    // Show report detail
    function showReportDetail(reportId) {
        // Fetch report detail
        fetch(`{{ url('admin/orders/recap') }}/${reportId}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                const modal = document.getElementById('reportDetailModal');
                
                // Simpan semua order data untuk filtering
                allOrdersData = data.order_summary || [];
                
                // Populate filter dropdowns
                populateFilters(allOrdersData);
                
                // Render orders (tanpa filter pertama kali)
                renderOrders(allOrdersData);
                
                // Update stats
                updateFilterStats(allOrdersData);
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat detail tutup hari',
                    confirmButtonColor: '#8b5cf6'
                });
            });
    }

    // Populate filter dropdowns
    function populateFilters(orders) {
        const tableSelect = document.getElementById('filterTable');
        const roomSelect = document.getElementById('filterRoom');
        
        // Get unique tables and rooms
        const tables = [...new Set(orders.map(o => o.table_number))].sort();
        const rooms = [...new Set(orders.map(o => o.room))].sort();
        
        // Clear existing options (except "Semua")
        tableSelect.innerHTML = '<option value="">Semua Meja</option>';
        roomSelect.innerHTML = '<option value="">Semua Ruangan</option>';
        
        // Add table options
        tables.forEach(table => {
            const option = document.createElement('option');
            option.value = table;
            option.textContent = `Meja ${table}`;
            tableSelect.appendChild(option);
        });
        
        // Add room options
        rooms.forEach(room => {
            const option = document.createElement('option');
            option.value = room;
            option.textContent = room;
            roomSelect.appendChild(option);
        });
        
        // Add event listeners for filtering
        tableSelect.addEventListener('change', applyFilters);
        roomSelect.addEventListener('change', applyFilters);
    }

    // Apply filters
    function applyFilters() {
        const selectedTable = document.getElementById('filterTable').value;
        const selectedRoom = document.getElementById('filterRoom').value;
        
        let filteredOrders = allOrdersData;
        
        if (selectedTable) {
            filteredOrders = filteredOrders.filter(order => order.table_number === selectedTable);
        }
        
        if (selectedRoom) {
            filteredOrders = filteredOrders.filter(order => order.room === selectedRoom);
        }
        
        renderOrders(filteredOrders);
        updateFilterStats(filteredOrders);
    }

    // Reset filter
    function resetFilter() {
        document.getElementById('filterTable').value = '';
        document.getElementById('filterRoom').value = '';
        renderOrders(allOrdersData);
        updateFilterStats(allOrdersData);
    }

    // Render orders
    function renderOrders(orders) {
        const content = document.getElementById('reportDetailContent');
        
        if (!orders || orders.length === 0) {
            content.innerHTML = '<div class="text-center py-12"><p class="text-gray-400">Tidak ada order yang sesuai dengan filter.</p></div>';
            return;
        }
        
        let html = '<div class="space-y-4">';
        
        orders.forEach((order, index) => {
            html += `
                <div class="bg-black/40 border border-white/10 rounded-xl p-4 hover:border-purple-500/50 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-white">Order #${order.id}</h4>
                            <p class="text-sm text-gray-400">${order.customer_name} - Meja ${order.table_number} (${order.room})</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-400">Rp ${parseInt(order.total_price).toLocaleString('id-ID')}</p>
                            <p class="text-xs text-gray-400 capitalize">${order.payment_method}</p>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        ${order.items.map(item => `
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-300">${item.menu_name} x${item.quantity}</span>
                                <span class="text-gray-400">Rp ${parseInt(item.price * item.quantity).toLocaleString('id-ID')}</span>
                            </div>
                        `).join('')}
                    </div>
                    <p class="text-xs text-gray-500 mt-2">${order.created_at}</p>
                </div>
            `;
        });
        
        html += '</div>';
        content.innerHTML = html;
    }

    // Update filter stats
    function updateFilterStats(orders) {
        const statsDiv = document.getElementById('filterStats');
        
        if (!orders || orders.length === 0) {
            statsDiv.innerHTML = '<span class="text-gray-500">Tidak ada order yang sesuai dengan filter.</span>';
            return;
        }
        
        const totalRevenue = orders.reduce((sum, order) => sum + parseFloat(order.total_price), 0);
        const totalOrders = orders.length;
        
        statsDiv.innerHTML = `
            <div class="flex gap-6">
                <span class="text-purple-400"><i class="ri-file-list-3-line"></i> ${totalOrders} Order</span>
                <span class="text-green-400"><i class="ri-money-dollar-circle-line"></i> Total: Rp ${totalRevenue.toLocaleString('id-ID')}</span>
            </div>
        `;
    }

    // Close report detail
    function closeReportDetail() {
        const modal = document.getElementById('reportDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // Reset filters
        document.getElementById('filterTable').value = '';
        document.getElementById('filterRoom').value = '';
        allOrdersData = [];
    }

    // Update tutup hari
    function updateRecap(reportId, startDate, endDate) {
        document.getElementById('updateRecapId').value = reportId;
        document.getElementById('updateStartDate').value = startDate;
        document.getElementById('updateEndDate').value = endDate;
        
        const modal = document.getElementById('updateRecapModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close update recap modal
    function closeUpdateRecapModal() {
        const modal = document.getElementById('updateRecapModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Handle update recap form submission
    document.getElementById('updateRecapForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const reportId = document.getElementById('updateRecapId').value;
        const startDate = document.getElementById('updateStartDate').value;
        const endDate = document.getElementById('updateEndDate').value;
        
        if (!startDate || !endDate) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Mohon isi tanggal mulai dan tanggal akhir',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        Swal.fire({
            title: 'Memperbarui Tutup Hari...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch(`{{ url('admin/orders/recap') }}/${reportId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.message,
                    confirmButtonColor: '#f97316'
                }).then(() => {
                    closeUpdateRecapModal();
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.message,
                    confirmButtonColor: '#f97316'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat memperbarui tutup hari',
                confirmButtonColor: '#f97316'
            });
        }
    });

    // Download tutup hari
    // Print tutup hari
    function printRecap(reportId) {
        const printContainer = document.getElementById('recap-' + reportId);
        if (!printContainer) {
            alert('Tutup hari tidak ditemukan');
            return;
        }

        // Clone hanya receipt-content, tanpa action buttons
        const receiptContent = printContainer.querySelector('.receipt-content');
        if (!receiptContent) {
            alert('Konten tutup hari tidak ditemukan');
            return;
        }

        // Clone seluruh receipt content untuk mendapatkan HTML lengkap
        const clonedContent = receiptContent.cloneNode(true);
        
        // Buat window baru untuk print
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Tutup Hari #${reportId}</title>
                <meta charset="utf-8">
                <style>
                    @page {
                        size: 80mm auto;
                        margin: 5mm;
                    }
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    html, body {
                        width: 80mm;
                        margin: 0 auto;
                        font-family: 'Courier New', monospace;
                        color: #000;
                        background: white;
                    }
                    .receipt-content {
                        border: none;
                        padding: 15px;
                        background: white;
                        width: 100%;
                    }
                    .receipt-header {
                        text-align: center;
                        margin-bottom: 15px;
                    }
                    .receipt-title {
                        font-size: 20px;
                        font-weight: bold;
                        margin: 0 0 5px 0;
                        color: #000;
                    }
                    .receipt-address {
                        font-size: 11px;
                        margin: 5px 0;
                        color: #000;
                    }
                    .receipt-id {
                        font-size: 10px;
                        margin: 5px 0;
                        color: #000;
                        letter-spacing: 1px;
                    }
                    .receipt-divider {
                        border-top: 1px dashed #000;
                        margin: 15px 0;
                    }
                    .receipt-info {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 15px;
                        font-size: 11px;
                    }
                    .receipt-info-left {
                        text-align: left;
                    }
                    .receipt-info-right {
                        text-align: right;
                    }
                    .receipt-date,
                    .receipt-time,
                    .receipt-number,
                    .receipt-staff {
                        margin: 2px 0;
                        color: #000;
                    }
                    .receipt-items {
                        margin-bottom: 15px;
                    }
                    .receipt-item {
                        margin-bottom: 8px;
                        font-size: 11px;
                    }
                    .receipt-item-name {
                        margin-bottom: 3px;
                        color: #000;
                    }
                    .receipt-item-details {
                        display: flex;
                        justify-content: space-between;
                        color: #000;
                    }
                    .receipt-summary {
                        margin-bottom: 15px;
                    }
                    .receipt-summary-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 5px;
                        font-size: 11px;
                        color: #000;
                    }
                    .receipt-footer {
                        text-align: center;
                        font-size: 10px;
                        margin-top: 15px;
                        color: #000;
                    }
                    .receipt-footer p {
                        margin: 3px 0;
                    }
                </style>
            </head>
            <body>
                ${clonedContent.outerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        
        // Wait for content to load then print
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 500);
    }

    function downloadRecap(reportId) {
        window.location.href = `{{ url('admin/orders/recap') }}/${reportId}/export`;
    }

    // Send recap email
    function sendRecapEmail(reportId) {
        Swal.fire({
            title: 'Kirim Tutup Hari ke Email',
            html: `
                <div class="text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Tujuan</label>
                    <input type="email" id="recapEmail" class="swal2-input" placeholder="nama@example.com" required>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#8b5cf6',
            cancelButtonColor: '#6b7280',
            preConfirm: () => {
                const email = document.getElementById('recapEmail').value;
                if (!email) {
                    Swal.showValidationMessage('Email harus diisi');
                    return false;
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    Swal.showValidationMessage('Format email tidak valid');
                    return false;
                }
                return email;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const email = result.value;
                
                Swal.fire({
                    title: 'Mengirim Email...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`{{ url('admin/orders/recap') }}/${reportId}/send-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#8b5cf6'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message,
                            confirmButtonColor: '#8b5cf6'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengirim email',
                        confirmButtonColor: '#8b5cf6'
                    });
                });
            }
        });
    }
</script>
@endpush
@endsection

