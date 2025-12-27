@extends('layouts.app')

@section('title', 'Menu - Billiard Class')

@section('content')
    <section style="padding: 0; margin: 0;">
        <div class="menu-container">
            <div class="find-food-section" id="menuSection">
                <div class="find-food-header">
                    <h2>Menu Class Billiard</h2>
                    <button class="view-order-btn" id="viewOrderBtn">Lihat Pesanan</button>
                </div>

                {{-- FILTER BAR: Dynamic Categories (sama seperti admin) --}}
                <div class="flex items-center gap-6 border-b border-white/5 pb-2 overflow-x-auto no-scrollbar mb-6">
                    <button class="category-filter-btn active pb-2 px-1 border-b-2 border-[#fa9a08] text-[#fa9a08] text-[10px] font-black uppercase tracking-widest whitespace-nowrap" data-category="all">
                        All Items
                    </button>
                    @foreach($categories as $category)
                        <button class="category-filter-btn pb-2 px-1 border-b-2 border-transparent text-gray-400 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap" data-category="{{ $category->slug }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                {{-- MAIN GRID: Professional Sleek Cards (sama seperti admin) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="menuItemsGrid">
                    @php
                        $allMenus = collect();
                        foreach($categories as $category) {
                            foreach($category->menus as $menu) {
                                $allMenus->push((object)[
                                    'menu' => $menu,
                                    'category' => $category
                                ]);
                            }
                        }
                    @endphp
                    
                    @forelse($allMenus as $item)
                        @php
                            $menu = $item->menu;
                            $category = $item->category;
                        @endphp
                        <div class="group flex flex-col transition-all duration-300 menu-item-card" data-category="{{ $category->slug }}">
                            {{-- Image Area: Precise Radius --}}
                            <div class="relative aspect-square overflow-hidden rounded-lg bg-white/5 border border-white/5">
                                @if($menu->image_path)
                                    <img src="{{ asset($menu->image_path) }}" 
                                         alt="{{ $menu->name }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-white/5">
                                        <i class="ri-image-line text-4xl text-gray-600"></i>
                    </div>
                                @endif

                                {{-- Floating Price --}}
                                <div class="absolute bottom-3 right-3">
                                    <div class="bg-[#fa9a08] text-black px-2.5 py-1 rounded text-[10px] font-black shadow-lg uppercase tracking-tighter">
                                        IDR {{ number_format($menu->price, 0, ',', '.') }}
                    </div>
                </div>

                                {{-- Category Label --}}
                                <div class="absolute top-3 left-3">
                                    <span class="px-2 py-0.5 bg-black/60 backdrop-blur-md text-white text-[8px] font-bold uppercase tracking-[0.2em] rounded border border-white/10">
                                        {{ $category->name }}
                                    </span>
                    </div>
                </div>

                            {{-- Content Area: Text Focused --}}
                            <div class="py-4 space-y-2 flex-1 flex flex-col">
                                {{-- Labels (PEDAS, Daging, dll) --}}
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($menu->labels ?? [] as $label)
                                        <span class="text-[8px] font-black text-[#fa9a08] uppercase tracking-widest">{{ $label }}</span>
                                        @if(!$loop->last) <span class="text-gray-600">•</span> @endif
                                    @empty
                                        <span class="text-[8px] font-bold text-gray-600 uppercase tracking-widest">Regular</span>
                                    @endforelse
                    </div>

                                {{-- Menu Name --}}
                                <h3 class="text-sm font-bold text-white group-hover:text-[#fa9a08] transition-colors leading-tight">
                                    {{ $menu->name }}
                                </h3>

                                {{-- Short Description --}}
                                @if($menu->short_description)
                                    <p class="text-[11px] text-gray-500 font-medium leading-relaxed line-clamp-2">
                                        {{ $menu->short_description }}
                                    </p>
                                @endif

                                {{-- Action: Tambah Button (beda dengan admin yang punya edit/delete) --}}
                                <div class="pt-4 flex items-center justify-between mt-auto">
                                    <span class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em]">
                                        Menu Item #{{ $menu->id }}
                                    </span>

                                    <button class="add-to-cart-btn bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded transition-all shadow-sm flex items-center gap-1" 
                                            data-name="{{ $menu->name }}" 
                                            data-price="{{ $menu->price }}"
                                            data-image="{{ $menu->image_path ? asset($menu->image_path) : '' }}"
                                            data-category="{{ $category->slug }}">
                                        <i class="ri-add-circle-line"></i> Tambah
                                    </button>
                    </div>
                </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20">
                            <i class="ri-restaurant-line text-6xl text-gray-600 mb-4 block"></i>
                            <p class="text-gray-400">Belum ada menu yang tersedia.</p>
                        </div>
                    @endforelse
    </div>
    </div>
   
            <!-- Order Panel -->
            <div class="order-panel" id="orderPanel">
                <div class="order-panel-header">
                    <h3>Pesanan Saya</h3>
                    <button class="close-order-btn" id="closeOrderBtn" title="Tutup">
                        <span class="close-x">×</span>
                    </button>
                </div>
                <div class="order-scrollable-content">
                    <div class="order-items-list" id="orderItemsList">
                        <!-- Items will be dynamically inserted here -->
                    </div>
                    <div class="order-summary">
                        <div class="order-summary-items" id="orderSummaryItems">
                            <!-- Summary items will be inserted here -->
                        </div>
                        <div class="order-total-section">
                            <span>Total Harga</span>
                            <span class="order-total-price" id="orderTotalPrice">Rp0</span>
                        </div>
                    </div>
                </div>
                <div class="payment-options">
                    <button class="payment-btn active" data-payment="cash">Cash</button>
                    <button class="payment-btn" data-payment="qris">Qris</button>
                    <button class="payment-btn" data-payment="transfer">Transfer</button>
                </div>
                <button class="checkout-btn" id="checkoutBtn">Checkout</button>
            </div>
    </div>
</section>

    <!-- Order Panel Overlay -->
    <div class="order-panel-overlay" id="orderPanelOverlay"></div>

    <!-- Bottom Order Bar -->
    <div class="bottom-order-bar" id="bottomOrderBar" style="display: none;">
        <div class="order-info-left">
            <div class="order-count" id="orderCount">0 Tambahan</div>
            <div class="restaurant-name">Class Billiard Eatery</div>
        </div>
        <div class="order-info-right">
            <span class="order-total" id="orderTotal">0</span>
            <i class="ri-shopping-bag-line"></i>
            <button class="close-bottom-bar-btn" id="closeBottomBarBtn" title="Tutup">
                <span class="close-x">×</span>
            </button>
    </div>
    </div>

    <!-- Checkout Form Modal -->
    <div class="checkout-modal" id="checkoutModal">
        <div class="checkout-modal-content">
            <div class="checkout-modal-header">
                <h3>Informasi Pesanan</h3>
                <button class="close-modal-btn" id="closeModalBtn">
                    <span class="close-x">×</span>
                </button>
            </div>
            <form id="checkoutForm">
                <div class="form-group">
                    <label for="customerName">Nama Pemesan</label>
                    <input type="text" id="customerName" name="customer_name" required placeholder="Masukkan nama pemesan">
                </div>
                <div class="form-group">
                    <label for="tableNumber">Nomor Meja</label>
                    <input type="text" id="tableNumber" name="table_number" required placeholder="Masukkan nomor meja">
                </div>
                <div class="form-group">
                    <label for="room">Ruangan</label>
                    <input type="text" id="room" name="room" required placeholder="Masukkan ruangan">
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelCheckoutBtn">Batal</button>
                    <button type="submit" class="submit-btn">Pesan Sekarang</button>
                </div>
            </form>
        </div>
    </div>
   
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('js/menu.js') }}"></script>
    @endpush
@endsection
