{{-- @extends('layouts.app')

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
 --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Grid Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        .receipt-font { font-family: 'Courier Prime', monospace; }
        .logo-font { font-family: 'Bebas Neue', cursive; }
        
        .kitchen-bg {
            background-color: #1a1a1a;
            background-image: url("https://www.transparenttextures.com/patterns/dark-wood.png");
        }

        /* Animasi Pop-up */
        @keyframes popup-bounce {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-popup { animation: popup-bounce 0.3s ease-out forwards; }

        /* Kertas Nota */
        .thermal-paper { 
            background: #fdfcf0; 
            box-shadow: 10px 10px 20px rgba(0,0,0,0.6); 
            transition: all 0.3s ease;
        }
        .jagged-bottom { 
            clip-path: polygon(0% 0%, 100% 0%, 100% 96%, 98% 100%, 96% 96%, 94% 100%, 92% 96%, 90% 100%, 88% 96%, 86% 100%, 84% 96%, 82% 100%, 80% 96%, 78% 100%, 76% 96%, 74% 100%, 72% 96%, 70% 100%, 68% 96%, 66% 100%, 64% 96%, 62% 100%, 60% 96%, 58% 100%, 56% 96%, 54% 100%, 52% 96%, 50% 100%, 48% 96%, 46% 100%, 44% 96%, 42% 100%, 40% 96%, 38% 100%, 36% 96%, 34% 100%, 32% 96%, 30% 100%, 28% 96%, 26% 100%, 24% 96%, 22% 100%, 20% 96%, 18% 100%, 16% 96%, 14% 100%, 12% 96%, 10% 100%, 8% 96%, 6% 100%, 4% 96%, 2% 100%, 0% 96%); 
        }
    </style>
</head>
<body class="kitchen-bg min-h-screen flex flex-col">

    <div id="order-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/95">
        <div class="bg-yellow-400 p-1 w-[90%] max-w-lg animate-popup">
            <div class="bg-black text-yellow-400 p-4 flex justify-between items-center">
                <h2 class="logo-font text-4xl">NEW ORDER!</h2>
                <span id="modal-time" class="font-mono font-bold">--:--</span>
            </div>
            <div class="p-8 bg-white text-black">
                <h3 id="modal-table" class="text-6xl font-black italic mb-6">TABLE --</h3>
                <div class="border-y-4 border-black border-double py-6">
                    <ul id="modal-items" class="receipt-font text-3xl space-y-3"></ul>
                </div>
                <button onclick="acceptOrder()" class="mt-8 w-full bg-green-600 text-white text-4xl font-black py-8 border-b-8 border-green-800 active:border-b-0 active:translate-y-2 transition-all">
                    TERIMA & MASAK
                </button>
            </div>
        </div>
    </div>

    <header class="p-6 bg-zinc-900 border-b-4 border-zinc-800 flex justify-between items-center sticky top-0 z-50">
        <h1 class="logo-font text-4xl text-white tracking-widest italic">FAT PANDA <span class="text-red-600">KITCHEN</span></h1>
        <button onclick="simulateIncomingOrder()" class="bg-red-600 text-white px-8 py-3 rounded font-black hover:bg-red-700 animate-pulse">
            SIMULASI ORDER MASUK
        </button>
    </header>

    <div class="w-full h-6 bg-zinc-800 shadow-inner border-b border-black"></div>

    <main id="order-grid" class="p-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12">
        </main>

    <script>
        let currentOrder = null;

        function simulateIncomingOrder() {
            currentOrder = {
                id: Math.floor(Math.random() * 9000) + 1000,
                table: "TABLE " + (Math.floor(Math.random() * 15) + 1),
                items: [
                    { qty: 1, name: "AYAM BAKAR MADU" },
                    { qty: 2, name: "ES TEH MANIS" }
                ],
                time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
            };

            document.getElementById('modal-table').innerText = currentOrder.table;
            document.getElementById('modal-time').innerText = currentOrder.time;
            document.getElementById('modal-items').innerHTML = currentOrder.items.map(i => 
                `<li><b>${i.qty}x</b> ${i.name}</li>`
            ).join('');

            document.getElementById('order-modal').classList.remove('hidden');
        }

        function acceptOrder() {
            document.getElementById('order-modal').classList.add('hidden');
            
            const grid = document.getElementById('order-grid');
            const newCard = document.createElement('div');
            
            // Random sedikit rotasi agar terlihat alami menempel
            const randomRotate = (Math.random() * 3 - 1.5).toFixed(1);

            newCard.className = `relative thermal-paper jagged-bottom p-6 pt-12 transform rotate-[${randomRotate}deg]`;
            newCard.style.transform = `rotate(${randomRotate}deg)`;
            
            newCard.innerHTML = `
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-14 h-16 bg-zinc-800 rounded shadow-xl flex justify-center pt-2">
                    <div class="w-10 h-1.5 bg-zinc-600 rounded"></div>
                </div>
                
                <div class="border-b-2 border-black border-dashed pb-3 mb-4">
                    <div class="flex justify-between font-bold text-[10px] text-gray-500">
                        <span>#${currentOrder.id}</span>
                        <span>${currentOrder.time}</span>
                    </div>
                    <h2 class="text-3xl font-black text-black leading-tight uppercase font-sans italic">${currentOrder.table}</h2>
                </div>
                
                <ul class="receipt-font text-gray-900 space-y-3 mb-10 text-xl">
                    ${currentOrder.items.map(i => `<li><b>${i.qty}x</b> ${i.name}</li>`).join('')}
                </ul>
                
                <button onclick="this.parentElement.remove()" class="w-full py-4 bg-black text-white font-black hover:bg-red-600 transition-colors uppercase">
                    SELESAI
                </button>
            `;

            grid.appendChild(newCard); // Gunakan appendChild agar pesanan baru nambah di belakang, atau prepend jika ingin di depan
        }
    </script>
</body>
</html>