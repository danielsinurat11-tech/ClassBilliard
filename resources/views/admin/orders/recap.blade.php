@extends('layouts.admin')

@section('title', 'Rekapitulasi Laporan')

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
</style>
@endpush

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Rekapitulasi Laporan</h1>
            <p class="text-gray-500 text-sm">Rekap order yang sudah selesai menjadi laporan. Setelah di-rekap, histori order akan clear.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl transition-all flex items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Kembali ke Manajemen Order
        </a>
    </div>

    {{-- Form Rekapitulasi --}}
    <div class="recap-card">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            <i class="ri-file-add-line text-purple-400"></i>
            Buat Rekapitulasi Baru
        </h2>
        <form id="recapForm" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50 focus:border-purple-600 transition-all"
                        value="{{ date('Y-m-d', strtotime('-7 days')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50 focus:border-purple-600 transition-all"
                        value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                <i class="ri-file-list-3-line"></i>
                Buat Rekapitulasi
            </button>
        </form>
    </div>

    {{-- Daftar Rekapitulasi --}}
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="ri-history-line text-purple-400"></i>
            Histori Rekapitulasi
        </h2>

        @if($reports->isEmpty())
        <div class="recap-card text-center py-12">
            <i class="ri-file-list-3-line text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400">Belum ada rekapitulasi yang dibuat.</p>
        </div>
        @else
        @foreach($reports as $report)
        <div class="recap-card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-white mb-1">
                        Rekapitulasi #{{ $report->id }}
                    </h3>
                    <p class="text-sm text-gray-400">
                        Periode: {{ \Carbon\Carbon::parse($report->start_date)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($report->end_date)->format('d M Y') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Dibuat: {{ \Carbon\Carbon::parse($report->created_at)->utc()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB oleh {{ $report->created_by }}
                    </p>
                </div>
                <span class="bg-purple-600/20 text-purple-400 px-3 py-1 rounded-lg text-sm font-medium">
                    {{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                <div class="stat-card">
                    <p class="text-xs text-gray-400 mb-1">Total Order</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($report->total_orders) }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs text-gray-400 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-400">Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs text-gray-400 mb-1">Cash</p>
                    <p class="text-lg font-bold text-white">Rp {{ number_format($report->cash_revenue, 0, ',', '.') }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs text-gray-400 mb-1">QRIS</p>
                    <p class="text-lg font-bold text-white">Rp {{ number_format($report->qris_revenue, 0, ',', '.') }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs text-gray-400 mb-1">Transfer</p>
                    <p class="text-lg font-bold text-white">Rp {{ number_format($report->transfer_revenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex gap-3 mt-4 flex-wrap">
                <button onclick="showReportDetail({{ $report->id }})" class="flex-1 bg-purple-600/20 hover:bg-purple-600/30 text-purple-400 hover:text-purple-300 font-medium py-2 px-4 rounded-xl transition-all flex items-center justify-center gap-2 min-w-[140px]">
                    <i class="ri-eye-line"></i>
                    Lihat Detail
                </button>
                <button onclick="updateRecap({{ $report->id }}, '{{ $report->start_date }}', '{{ $report->end_date }}')" class="flex-1 bg-orange-600/20 hover:bg-orange-600/30 text-orange-400 hover:text-orange-300 font-medium py-2 px-4 rounded-xl transition-all flex items-center justify-center gap-2 min-w-[140px]">
                    <i class="ri-refresh-line"></i>
                    Perbarui
                </button>
                <button onclick="downloadRecap({{ $report->id }})" class="flex-1 bg-green-600/20 hover:bg-green-600/30 text-green-400 hover:text-green-300 font-medium py-2 px-4 rounded-xl transition-all flex items-center justify-center gap-2 min-w-[140px]">
                    <i class="ri-download-line"></i>
                    Download
                </button>
                <button onclick="sendRecapEmail({{ $report->id }})" class="flex-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 hover:text-blue-300 font-medium py-2 px-4 rounded-xl transition-all flex items-center justify-center gap-2 min-w-[140px]">
                    <i class="ri-mail-line"></i>
                    Kirim Email
                </button>
            </div>
        </div>
        @endforeach

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
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
                    Perbarui Rekapitulasi
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
                        <span>Rekapitulasi akan diperbarui dengan data terbaru. Order baru yang completed untuk periode ini akan ditambahkan ke rekapitulasi.</span>
                    </p>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeUpdateRecapModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="ri-refresh-line"></i>
                        Perbarui Rekapitulasi
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
                <h3 class="text-xl font-bold text-white">Detail Rekapitulasi</h3>
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
    // Handle form rekapitulasi
    document.getElementById('recapForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (!startDate || !endDate) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Mohon isi tanggal mulai dan tanggal akhir',
                confirmButtonColor: '#8b5cf6'
            });
            return;
        }

        Swal.fire({
            title: 'Memproses Rekapitulasi...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('{{ route("admin.orders.recap") }}', {
                method: 'POST',
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
                    confirmButtonColor: '#8b5cf6'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.message,
                    confirmButtonColor: '#8b5cf6'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat membuat rekapitulasi',
                confirmButtonColor: '#8b5cf6'
            });
        }
    });

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
                    text: 'Gagal memuat detail rekapitulasi',
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

    // Update rekapitulasi
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
            title: 'Memperbarui Rekapitulasi...',
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
                text: 'Terjadi kesalahan saat memperbarui rekapitulasi',
                confirmButtonColor: '#f97316'
            });
        }
    });

    // Download rekapitulasi
    function downloadRecap(reportId) {
        window.location.href = `{{ url('admin/orders/recap') }}/${reportId}/export`;
    }

    // Send recap email
    function sendRecapEmail(reportId) {
        Swal.fire({
            title: 'Kirim Rekapitulasi ke Email',
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

