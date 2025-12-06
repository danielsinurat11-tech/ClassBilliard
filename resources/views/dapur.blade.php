@extends('layouts.app')

@section('title', 'Dapur - Billiard Class')

@section('content')
    <div class="py-8 min-h-screen max-md:py-4">
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
                                <p class="my-2"><strong class="font-semibold">Waktu Pesan :</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</p>
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

            <div class="gap-4 mb-6 items-center flex-wrap hidden" id="reportsSummary">
                <div class="bg-[#fa9a08] p-6 rounded-xl flex-1 min-w-[200px]">
                    <h3 class="text-white text-[0.9rem] font-medium m-0 mb-2">Total Pesanan</h3>
                    <p id="totalOrders" class="text-white text-2xl font-bold m-0">0</p>
                </div>
                <div class="bg-[#fa9a08] p-6 rounded-xl flex-1 min-w-[200px]">
                    <h3 class="text-white text-[0.9rem] font-medium m-0 mb-2">Total Pendapatan</h3>
                    <p id="totalRevenue" class="text-white text-2xl font-bold m-0">Rp0</p>
                </div>
                <button class="export-excel-btn py-3 px-6 bg-emerald-500 text-white border-none rounded-xl text-[0.95rem] font-semibold cursor-pointer transition-all duration-200 h-fit hover:bg-emerald-600" id="exportExcelBtn">Export Excel</button>
            </div>

            <div class="min-h-[200px]" id="reportsContent">
                <div class="text-center py-16 px-8 text-gray-400 text-lg">
                    <p>Pilih filter dan klik "Tampilkan" untuk melihat laporan</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/dapur.js') }}"></script>
    @endpush
@endsection

