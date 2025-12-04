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
                <div class="menu-category">
                    <button class="category-btn active" data-category="makanan">Makanan</button>
                    <button class="category-btn" data-category="minuman">Minuman</button>
                    <button class="category-btn" data-category="cemilan">Cemilan</button>
                    <button class="category-btn" data-category="all">Semua</button>
                </div>
                <div class="menu-items-grid" id="menuItemsGrid">
                {{-- Makanan --}}
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/MIE GORENG CLASS - 25K.png') }}" alt="Mie Goreng Class" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Mie Goreng Class</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn" data-name="Mie Goreng Class" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/MIE KUAH CLASS - 25K.png') }}" alt="Mie Kuah Class" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Mie Kuah Class</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn" data-name="Mie Kuah Class" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/NASI GORENG CLASS - 30K.png') }}" alt="Nasi Goreng Class" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Nasi Goreng Class</h3>
                            <p class="menu-item-price">Rp30.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn" data-name="Nasi Goreng Class" data-price="30000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/NASI TELUR CLASS - 30K.png') }}" alt="Nasi Telur Class" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Nasi Telur Class</h3>
                            <p class="menu-item-price">Rp30.000</p>
                        </div>
                        <p class="menu-item-description">Nasi dengan telur goreng spesial</p>
                        <button class="add-to-cart-btn" data-name="Nasi Telur Class" data-price="30000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/BEEF RICE BOWL - 45K.png') }}" alt="Beef Rice Bowl" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Beef Rice Bowl</h3>
                            <p class="menu-item-price">Rp45.000</p>
                        </div>
                        <p class="menu-item-description">Nasi dengan daging sapi yang lezat</p>
                        <button class="add-to-cart-btn" data-name="Beef Rice Bowl" data-price="45000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/MIX PLATER - 45K.png') }}" alt="Mix Plater" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Mix Plater</h3>
                            <p class="menu-item-price">Rp45.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn" data-name="Mix Plater" data-price="45000">Tambah</button>
                    </div>
                </div>

                {{-- Minuman --}}
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/AIR MINERAL - 10K.png') }}" alt="Air Mineral" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Air Mineral</h3>
                            <p class="menu-item-price">Rp10.000</p>
                        </div>
                        <p class="menu-item-description">Air mineral yang segar</p>
                        <button class="add-to-cart-btn" data-name="Air Mineral" data-price="10000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/AMERICANO ICED -25K.png') }}" alt="Americano Iced" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Americano Iced</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Kopi americano dingin yang menyegarkan</p>
                        <button class="add-to-cart-btn" data-name="Americano Iced" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/BLACK COFFE - 10K.png') }}" alt="Black Coffee" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Black Coffee</h3>
                            <p class="menu-item-price">Rp10.000</p>
                        </div>
                        <p class="menu-item-description">Kopi hitam yang nikmat</p>
                        <button class="add-to-cart-btn" data-name="Black Coffee" data-price="10000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/CHOCOLATE MILK - 20K.png') }}" alt="Chocolate Milk" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Chocolate Milk</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Susu coklat yang manis</p>
                        <button class="add-to-cart-btn" data-name="Chocolate Milk" data-price="20000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/EXTRAJOS SUSU - 18K.png') }}" alt="Extrajos Susu" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Extrajos Susu</h3>
                            <p class="menu-item-price">Rp18.000</p>
                        </div>
                        <p class="menu-item-description">Minuman extrajos dengan susu</p>
                        <button class="add-to-cart-btn" data-name="Extrajos Susu" data-price="18000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/KOPI SUSU CLASS - 23K.png') }}" alt="Kopi Susu Class" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Kopi Susu Class</h3>
                            <p class="menu-item-price">Rp23.000</p>
                        </div>
                        <p class="menu-item-description">Kopi susu dengan rasa yang khas</p>
                        <button class="add-to-cart-btn" data-name="Kopi Susu Class" data-price="23000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/KUKUBIMA SUSU - 18K.png') }}" alt="Kukubima Susu" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Kukubima Susu</h3>
                            <p class="menu-item-price">Rp18.000</p>
                        </div>
                        <p class="menu-item-description">Minuman kukubima dengan susu</p>
                        <button class="add-to-cart-btn" data-name="Kukubima Susu" data-price="18000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/LEMON TEA - 25K.png') }}" alt="Lemon Tea" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Lemon Tea</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Teh lemon yang menyegarkan</p>
                        <button class="add-to-cart-btn" data-name="Lemon Tea" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/LYCHEE TEA - 25K.png') }}" alt="Lychee Tea" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Lychee Tea</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Teh lychee yang manis</p>
                        <button class="add-to-cart-btn" data-name="Lychee Tea" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/MATCHA LATTE - 25K.png') }}" alt="Matcha Latte" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Matcha Latte</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Matcha latte yang creamy</p>
                        <button class="add-to-cart-btn" data-name="Matcha Latte" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/MILK BASIC - 18K.png') }}" alt="Milk Basic" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Milk Basic</h3>
                            <p class="menu-item-price">Rp18.000</p>
                        </div>
                        <p class="menu-item-description">Susu murni yang segar</p>
                        <button class="add-to-cart-btn" data-name="Milk Basic" data-price="18000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/NUTRISARI ORANGE - 10K.png') }}" alt="Nutrisari Orange" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Nutrisari Orange</h3>
                            <p class="menu-item-price">Rp10.000</p>
                        </div>
                        <p class="menu-item-description">Minuman nutrisari rasa jeruk</p>
                        <button class="add-to-cart-btn" data-name="Nutrisari Orange" data-price="10000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/TEA BASIC - 15K.png') }}" alt="Tea Basic" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Tea Basic</h3>
                            <p class="menu-item-price">Rp15.000</p>
                        </div>
                        <p class="menu-item-description">Teh basic yang nikmat</p>
                        <button class="add-to-cart-btn" data-name="Tea Basic" data-price="15000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/TEA TARIK - 25K.png') }}" alt="Tea Tarik" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Tea Tarik</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Teh tarik yang khas</p>
                        <button class="add-to-cart-btn" data-name="Tea Tarik" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/YAKULT ORANGE - 25K.png') }}" alt="Yakult Orange" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Yakult Orange</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Yakult rasa jeruk</p>
                        <button class="add-to-cart-btn" data-name="Yakult Orange" data-price="25000">Tambah</button>
                    </div>
                </div>

                {{-- Cemilan --}}
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/KENTANG BALADO - 25K.png') }}" alt="Kentang Balado" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Kentang Balado</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Kentang goreng dengan sambal balado</p>
                        <button class="add-to-cart-btn" data-name="Kentang Balado" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/SINGKONG GORENG - 20K.png') }}" alt="Singkong Goreng" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Singkong Goreng</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Singkong goreng yang renyah</p>
                        <button class="add-to-cart-btn" data-name="Singkong Goreng" data-price="20000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/SOSIS GORENG - 25K.png') }}" alt="Sosis Goreng" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Sosis Goreng</h3>
                            <p class="menu-item-price">Rp25.000</p>
                        </div>
                        <p class="menu-item-description">Sosis goreng yang gurih</p>
                        <button class="add-to-cart-btn" data-name="Sosis Goreng" data-price="25000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/TAHU KRISPI - 20K.png') }}" alt="Tahu Krispi" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Tahu Krispi</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Tahu goreng yang renyah dan crispy</p>
                        <button class="add-to-cart-btn" data-name="Tahu Krispi" data-price="20000">Tambah</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/img/TAHU WALIK - 20K.png') }}" alt="Tahu Walik" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Tahu Walik</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Tahu walik dengan isian yang lezat</p>
                        <button class="add-to-cart-btn" data-name="Tahu Walik" data-price="20000">Tambah</button>
                    </div>
                </div>
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
                <div class="order-panel-category">
                    <button class="order-category-btn active" data-order-category="all">Semua</button>
                    <button class="order-category-btn" data-order-category="makanan">Makanan</button>
                    <button class="order-category-btn" data-order-category="minuman">Minuman</button>
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
   
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('js/menu.js') }}"></script>
    @endpush
@endsection
