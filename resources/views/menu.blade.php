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
                            <img src="{{ asset('assets/img/MIE GORENG CLASS - 25K 2.png') }}" alt="Mie Goreng Class"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Mie Goreng Class</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Mie Goreng Class"
                                    data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="makanan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/MIE KUAH CLASS - 25K 2.png') }}" alt="Mie Kuah Class"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Mie Kuah Class</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Mie Kuah Class"
                                    data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="makanan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/NASI GORENG CLASS - 30K 2.png') }}" alt="Nasi Goreng Class"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Nasi Goreng Class</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp30.000</p>
                                <button class="add-to-cart-btn" data-name="Nasi Goreng Class"
                                    data-price="30000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="makanan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/NASI TELUR CLASS - 30K 2.png') }}" alt="Nasi Telur Class"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Nasi Telur Class</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp30.000</p>
                                <button class="add-to-cart-btn" data-name="Nasi Telur Class"
                                    data-price="30000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="makanan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/BEEF RICE BOWL - 45K 2.png') }}" alt="Beef Rice Bowl"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Beef Rice Bowl</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp45.000</p>
                                <button class="add-to-cart-btn" data-name="Beef Rice Bowl"
                                    data-price="45000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="cemilan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/MIX PLATER - 45K 2.png') }}" alt="Mix Plater"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Mix Plater</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp45.000</p>
                                <button class="add-to-cart-btn" data-name="Mix Plater" data-price="45000">Tambah</button>
                            </div>
                        </div>
                    </div>

                    {{-- Minuman --}}
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/AIR MINERAL - 10K 2.png') }}" alt="Air Mineral"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Air Mineral</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp10.000</p>
                                <button class="add-to-cart-btn" data-name="Air Mineral" data-price="10000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/AMERICANO ICED -25K 2.png') }}" alt="Americano Iced"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Americano Iced</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Americano Iced"
                                    data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/BLACK COFFE - 10K 2.png') }}" alt="Black Coffee"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Black Coffee</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp10.000</p>
                                <button class="add-to-cart-btn" data-name="Black Coffee" data-price="10000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/CHOCOLATE MILK - 20K 2.png') }}" alt="Chocolate Milk"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Chocolate Milk</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp20.000</p>
                                <button class="add-to-cart-btn" data-name="Chocolate Milk"
                                    data-price="20000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/EXTRAJOS SUSU - 18K 2.png') }}" alt="Extrajos Susu"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Extrajos Susu</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp18.000</p>
                                <button class="add-to-cart-btn" data-name="Extrajos Susu" data-price="18000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/KOPI SUSU CLASS - 23K 2.png') }}" alt="Kopi Susu Class"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Kopi Susu Class</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp23.000</p>
                                <button class="add-to-cart-btn" data-name="Kopi Susu Class"
                                    data-price="23000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/KUKUBIMA SUSU - 18K 2.png') }}" alt="Kukubima Susu"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Kukubima Susu</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp18.000</p>
                                <button class="add-to-cart-btn" data-name="Kukubima Susu" data-price="18000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/LEMON TEA - 25K 2.png') }}" alt="Lemon Tea"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Lemon Tea</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Lemon Tea" data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/LYCHEE TEA - 25K 2.png') }}" alt="Lychee Tea"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Lychee Tea</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Lychee Tea" data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/MATCHA LATTE - 25K 2.png') }}" alt="Matcha Latte"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Matcha Latte</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Matcha Latte" data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/MILK BASIC - 18K 2.png') }}" alt="Milk Basic"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Milk Basic</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp18.000</p>
                                <button class="add-to-cart-btn" data-name="Milk Basic" data-price="18000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/NUTRISARI ORANGE - 10K 2.png') }}" alt="Nutrisari Orange"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Nutrisari Orange</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp10.000</p>
                                <button class="add-to-cart-btn" data-name="Nutrisari Orange"
                                    data-price="10000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/ICED TEA BASIC - 15K 2.png') }}" alt="Tea Basic"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Tea Basic</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp15.000</p>
                                <button class="add-to-cart-btn" data-name="Tea Basic" data-price="15000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/TEA TARIK - 25K 2.png') }}" alt="Tea Tarik"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Tea Tarik</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Tea Tarik" data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="minuman">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/YAKULT ORANGE - 25K 2.png') }}" alt="Yakult Orange"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Yakult Orange</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Yakult Orange" data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>

                    {{-- Cemilan --}}
                    <div class="menu-item-card" data-category="cemilan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/KENTANG BALADO - 25K 2.png') }}" alt="Kentang Balado"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Kentang Balado</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Kentang Balado"
                                    data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="cemilan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/SINGKONG GORENG - 20K 2.png') }}" alt="Singkong Goreng"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Singkong Goreng</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp20.000</p>
                                <button class="add-to-cart-btn" data-name="Singkong Goreng"
                                    data-price="20000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="cemilan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/SOSIS GORENG - 25K 2.png') }}" alt="Sosis Goreng"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Sosis Goreng</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp25.000</p>
                                <button class="add-to-cart-btn" data-name="Sosis Goreng" data-price="25000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="cemilan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/TAHU KRISPI - 20K 2.png') }}" alt="Tahu Krispi"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Tahu Krispi</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp20.000</p>
                                <button class="add-to-cart-btn" data-name="Tahu Krispi" data-price="20000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card" data-category="cemilan">
                        <div class="menu-item-image-wrapper">
                            <img src="{{ asset('assets/img/TAHU WALIK - 20K 2.png') }}" alt="Tahu Walik"
                                class="menu-item-image">
                        </div>
                        <div class="menu-item-info">
                            <div class="menu-item-header">
                                <h3>Tahu Walik</h3>
                            </div>
                            <div class="menu-item-footer">
                                <p class="menu-item-price">Rp20.000</p>
                                <button class="add-to-cart-btn" data-name="Tahu Walik" data-price="20000">Tambah</button>
                            </div>
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