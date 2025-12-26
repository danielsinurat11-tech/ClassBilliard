@extends('layouts.admin')

@section('title', 'Manajemen Order')

@push('styles')
<style>
    .order-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s;
    }
    .order-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(250, 154, 8, 0.3);
        transform: translateY(-2px);
    }
    .order-card.opacity-60 {
        opacity: 0.6;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
    }
    .status-processing {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
    }
    .status-rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    .status-completed {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }
    .status-label {
        font-size: 12px;
        font-weight: 600;
    }
    .status-label-pending {
        color: #fbbf24;
    }
    .status-label-processing {
        color: #3b82f6;
    }
    .status-label-rejected {
        color: #ef4444;
    }
    .status-label-completed {
        color: #22c55e;
    }
    .table-filter-card {
        background: rgba(255, 255, 255, 0.03);
        border: 2px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .table-filter-card:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(250, 154, 8, 0.4);
        transform: translateY(-2px);
    }
    .table-filter-card.active {
        background: linear-gradient(135deg, rgba(250, 154, 8, 0.2), rgba(250, 154, 8, 0.1));
        border-color: rgba(250, 154, 8, 0.6);
        box-shadow: 0 4px 12px rgba(250, 154, 8, 0.2);
    }
    .table-filter-card .table-number {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin-bottom: 2px;
        line-height: 1.2;
    }
    .table-filter-card .table-label {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    @media (max-width: 640px) {
        .table-filter-card {
            padding: 10px 12px;
            min-height: 60px;
        }
        .table-filter-card .table-number {
            font-size: 16px;
        }
        .table-filter-card .table-label {
            font-size: 9px;
        }
    }
    .order-card[data-table] {
        display: block;
    }
    .order-card.hidden {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Manajemen Order</h1>
            <p class="text-gray-500 text-sm">Konfirmasi dan kelola pesanan dari pelanggan.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-xs text-gray-500 mb-1">Total Orders</p>
                <p class="text-2xl font-bold text-white">{{ $allOrders->count() }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl">
        <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl">
        <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
    </div>
    @endif

    {{-- Filter Meja --}}
    <div class="mb-6">
        <p class="text-gray-400 text-sm mb-3 font-semibold">Filter berdasarkan Meja:</p>
        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-9 lg:grid-cols-9 xl:grid-cols-9 gap-3">
            {{-- Card Semua Meja --}}
            <div class="table-filter-card active" data-table="all" onclick="filterByTable('all')">
                <div class="table-number">Semua</div>
                <div class="table-label">Meja</div>
            </div>
            {{-- Card Meja 1-17 --}}
            @for($i = 1; $i <= 17; $i++)
            <div class="table-filter-card" data-table="{{ $i }}" onclick="filterByTable('{{ $i }}')">
                <div class="table-number">{{ $i }}</div>
                <div class="table-label">Meja</div>
            </div>
            @endfor
            {{-- Card Meja Lainnya --}}
            <div class="table-filter-card" data-table="other" onclick="filterByTable('other')">
                <div class="table-number">+</div>
                <div class="table-label">Lainnya</div>
            </div>
        </div>
    </div>

    {{-- All Orders List --}}
    @if($allOrders->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="ordersContainer">
        @foreach($allOrders as $order)
        @php
            $tableNum = $order->table_number;
            // Cek apakah table_number adalah angka atau string yang berisi angka
            $numericTable = is_numeric($tableNum) ? (int)$tableNum : (preg_match('/\d+/', $tableNum, $matches) ? (int)$matches[0] : null);
            $isStandardTable = $numericTable !== null && $numericTable >= 1 && $numericTable <= 17;
            $tableFilter = $isStandardTable ? $numericTable : 'other';
        @endphp
        <div class="order-card {{ $order->status === 'rejected' ? 'opacity-60' : '' }}" data-table="{{ $tableFilter }}">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-white">{{ $order->customer_name }}</h3>
                        @if($order->status === 'pending')
                            <span class="status-badge status-pending">Menunggu Konfirmasi</span>
                        @elseif($order->status === 'processing')
                            <span class="status-badge status-processing">Sedang Diproses</span>
                        @elseif($order->status === 'completed')
                            <span class="status-badge status-completed">Sudah Selesai</span>
                        @elseif($order->status === 'rejected')
                            <span class="status-badge status-rejected">Ditolak</span>
                        @endif
                    </div>
                    <p class="text-gray-400 text-sm">Meja {{ $order->table_number }} • {{ $order->room }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        Pesan: {{ $order->created_at->format('d M Y H:i') }}
                        @if($order->status === 'completed')
                        <br>Selesai: {{ $order->updated_at->format('d M Y H:i') }}
                        @endif
                    </p>
                </div>
                <div class="text-right ml-4">
                    <p class="text-2xl font-bold {{ $order->status === 'completed' ? 'text-green-500' : ($order->status === 'rejected' ? 'text-gray-500' : 'text-[var(--accent)]') }}">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ strtoupper($order->payment_method) }}</p>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-gray-400 text-sm mb-2 font-semibold">Pesanan:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($order->orderItems as $item)
                    <div class="bg-white/5 rounded-lg px-3 py-2 flex items-center gap-2">
                        @if($item->image)
                        <img src="{{ asset($item->image) }}" alt="{{ $item->menu_name }}" class="w-8 h-8 rounded object-cover">
                        @endif
                        <span class="text-white text-sm">{{ $item->quantity }}x {{ $item->menu_name }}</span>
                        <span class="text-gray-500 text-xs">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Action Buttons untuk Pending Orders --}}
            @if($order->status === 'pending')
            <div class="flex gap-3">
                <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="ri-check-line"></i>
                        Approve Order
                    </button>
                </form>
                <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2" onclick="return confirm('Yakin ingin menolak order ini?')">
                        <i class="ri-close-line"></i>
                        Tolak Order
                    </button>
                </form>
            </div>
            @endif

            {{-- Tombol Hapus untuk semua status --}}
            <div class="mt-3">
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus order ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="ri-delete-bin-line"></i>
                        Hapus Order
                    </button>
                </form>
            </div>

            {{-- Info Box untuk Processing Orders --}}
            @if($order->status === 'processing')
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                <p class="text-blue-400 text-sm flex items-center gap-2">
                    <i class="ri-information-line"></i>
                    Order ini sedang diproses di dapur
                </p>
            </div>
            @endif

            {{-- Info Box untuk Completed Orders --}}
            @if($order->status === 'completed')
            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4">
                <p class="text-green-400 text-sm flex items-center gap-2">
                    <i class="ri-checkbox-circle-line"></i>
                    Order ini sudah selesai diproses oleh dapur
                </p>
            </div>
            @endif

            {{-- Info Box untuk Rejected Orders --}}
            @if($order->status === 'rejected')
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <p class="text-red-400 text-sm flex items-center gap-2">
                    <i class="ri-close-circle-line"></i>
                    Order ini telah ditolak
                </p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16">
        <i class="ri-shopping-cart-line text-6xl text-gray-600 mb-4"></i>
        <p class="text-gray-500">Tidak ada order</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    let currentFilter = 'all';

    function filterByTable(table) {
        currentFilter = table;
        
        // Update active state pada filter cards
        document.querySelectorAll('.table-filter-card').forEach(card => {
            card.classList.remove('active');
        });
        const activeCard = document.querySelector(`.table-filter-card[data-table="${table}"]`);
        if (activeCard) {
            activeCard.classList.add('active');
        }
        
        // Filter order cards
        const orderCards = document.querySelectorAll('.order-card');
        let visibleCount = 0;
        
        orderCards.forEach(card => {
            const cardTable = card.getAttribute('data-table');
            
            if (table === 'all') {
                card.classList.remove('hidden');
                visibleCount++;
            } else if (cardTable === String(table)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
    }

    // Auto refresh setiap 5 detik untuk update order status
    setInterval(() => {
        location.reload();
    }, 5000);
</script>
@endpush
@endsection
