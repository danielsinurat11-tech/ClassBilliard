@extends('layouts.app')

@section('title', 'Detail Pesanan - Billiard Class')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<style>
    .order-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 100vh;
        background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
    }

    .order-header {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .order-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 1rem;
    }

    .status-processing {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .order-items-section {
        background: rgba(30, 30, 30, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .order-item:last-child {
        margin-bottom: 0;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .item-price {
        color: #a78bfa;
        font-weight: 600;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        border-radius: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background: rgba(139, 92, 246, 0.3);
        border-color: rgba(139, 92, 246, 0.5);
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }

    .remove-item-btn {
        padding: 0.5rem 1rem;
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .remove-item-btn:hover {
        background: rgba(239, 68, 68, 0.3);
    }

    .order-summary {
        background: rgba(30, 30, 30, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 2rem;
        position: sticky;
        top: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .summary-row:last-child {
        border-bottom: none;
        font-size: 1.25rem;
        font-weight: 700;
        color: #a78bfa;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid rgba(139, 92, 246, 0.3);
    }

    .add-more-section {
        background: rgba(30, 30, 30, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary {
        flex: 1;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        color: #fff;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
    }

    .btn-secondary {
        flex: 1;
        padding: 1rem 2rem;
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: rgba(239, 68, 68, 0.3);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #a78bfa;
        text-decoration: none;
        margin-bottom: 2rem;
        transition: all 0.2s;
    }

    .btn-back:hover {
        color: #c4b5fd;
    }

    /* Responsive Styles for Mobile and Tablet */
    @media (max-width: 768px) {
        .order-detail-container {
            padding: 1rem;
        }

        .order-header {
            padding: 1.5rem;
        }

        .order-header h1 {
            font-size: 1.5rem;
        }

        .order-items-section {
            padding: 1.5rem;
        }

        .order-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
        }

        .item-image {
            width: 60px;
            height: 60px;
        }

        .item-name {
            font-size: 1rem;
        }

        .quantity-controls {
            width: 100%;
            justify-content: space-between;
        }

        .order-summary {
            position: static;
            margin-top: 1rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }

        .add-more-section {
            padding: 1.5rem;
        }

        .order-completed-popup {
            padding: 2rem 1.5rem;
            max-width: 90%;
        }

        .order-completed-popup h2 {
            font-size: 1.5rem;
        }

        .order-completed-popup p {
            font-size: 1rem;
        }

        .order-completed-popup .emoji {
            font-size: 2.5rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .order-detail-container {
            padding: 1.5rem;
        }

        .order-header {
            padding: 1.75rem;
        }

        .order-items-section {
            padding: 1.75rem;
        }

        .order-item {
            padding: 1.25rem;
        }

        .order-completed-popup {
            padding: 2.5rem;
            max-width: 80%;
        }
    }

    .status-completed {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    /* Popup Notification Styles */
    .order-completed-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.95) 0%, rgba(16, 185, 129, 0.95) 100%);
        border: 2px solid rgba(34, 197, 94, 0.5);
        border-radius: 1.5rem;
        padding: 3rem;
        z-index: 10000;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        text-align: center;
        max-width: 500px;
        width: 90%;
        animation: popupSlideIn 0.5s ease-out;
    }

    @keyframes popupSlideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    .order-completed-popup h2 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .order-completed-popup p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.125rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .order-completed-popup .emoji {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .order-completed-popup button {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .order-completed-popup button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="order-detail-container">
    <a href="{{ route('menu') }}" class="btn-back">
        <i class="ri-arrow-left-line"></i>
        Kembali ke Menu
    </a>

    <div class="order-header">
        <h1 class="text-3xl font-bold text-white mb-2">Detail Pesanan</h1>
        <p class="text-gray-400">Order #{{ $order->id }}</p>
        <div class="mt-4">
            <p class="text-gray-300"><strong>Nama:</strong> {{ $order->customer_name }}</p>
            <p class="text-gray-300"><strong>Meja:</strong> {{ $order->table_number }} | <strong>Ruangan:</strong> {{ $order->room }}</p>
            <p class="text-gray-300"><strong>Metode Pembayaran:</strong> 
                <span class="capitalize">{{ $order->payment_method === 'cash' ? 'Tunai' : ($order->payment_method === 'qris' ? 'QRIS' : 'Transfer') }}</span>
            </p>
            <span class="order-status status-{{ $order->status }}" id="orderStatusBadge">
                @if($order->status === 'processing')
                    Sedang Diproses
                @elseif($order->status === 'pending')
                    Menunggu Konfirmasi
                @elseif($order->status === 'completed')
                    Selesai
                @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="order-items-section">
                <h2 class="text-xl font-bold text-white mb-4">Item Pesanan</h2>
                <div id="orderItemsList">
                    @foreach($order->orderItems as $item)
                    <div class="order-item" data-item-id="{{ $item->id }}">
                        @if($item->image)
                        @php
                            // Fix image path - jika sudah absolute, gunakan langsung, jika relative, tambahkan asset()
                            $imagePath = $item->image;
                            if (!str_starts_with($imagePath, 'http') && !str_starts_with($imagePath, '/')) {
                                $imagePath = asset($item->image);
                            } elseif (str_starts_with($imagePath, '/') && !str_starts_with($imagePath, 'http')) {
                                $imagePath = asset(ltrim($item->image, '/'));
                            }
                        @endphp
                        <img src="{{ $imagePath }}" alt="{{ $item->menu_name }}" class="item-image" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'item-image bg-gray-700 flex items-center justify-center\'><i class=\'ri-image-line text-2xl text-gray-500\'></i></div>';">
                        @else
                        <div class="item-image bg-gray-700 flex items-center justify-center">
                            <i class="ri-image-line text-2xl text-gray-500"></i>
                        </div>
                        @endif
                        <div class="item-info">
                            <div class="item-name">{{ $item->menu_name }}</div>
                            <div class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="quantity-controls">
                            <button class="quantity-btn decrease-qty" data-item-id="{{ $item->id }}">
                                <i class="ri-subtract-line"></i>
                            </button>
                            <input type="number" class="quantity-input" value="{{ $item->quantity }}" min="1" data-item-id="{{ $item->id }}" readonly>
                            <button class="quantity-btn increase-qty" data-item-id="{{ $item->id }}">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                        <div class="text-right">
                            <div class="item-price text-lg mb-2">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                            <button class="remove-item-btn" data-item-id="{{ $item->id }}">
                                <i class="ri-delete-bin-line"></i> Hapus
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="add-more-section">
                <h2 class="text-xl font-bold text-white mb-4">Tambah Item Lain</h2>
                <a href="{{ route('menu', ['order_id' => $order->id]) }}" class="btn-primary inline-flex items-center justify-center gap-2">
                    <i class="ri-add-line"></i>
                    Pilih Menu Lain
                </a>
            </div>
        </div>

        <div>
            <div class="order-summary">
                <h2 class="text-xl font-bold text-white mb-4">Ringkasan</h2>
                <div class="summary-row">
                    <span class="text-gray-400">Total Item</span>
                    <span class="text-white font-semibold" id="totalItems">{{ $order->orderItems->sum('quantity') }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-white font-semibold" id="subtotal">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Total Pembayaran</span>
                    <span id="totalPrice">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="action-buttons">
                <button id="saveOrderBtn" class="btn-primary">
                    <i class="ri-save-line"></i> Simpan Perubahan
                </button>
                <button id="cancelOrderBtn" class="btn-secondary">
                    <i class="ri-close-line"></i> Batalkan Order
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const orderId = {{ $order->id }};
    let orderItems = @json($order->orderItems);
    let currentStatus = '{{ $order->status }}';
    let popupShown = false;

    // Check order status periodically
    function checkOrderStatus() {
        // Only check if order is still processing or pending
        if (currentStatus === 'completed' || currentStatus === 'rejected') {
            return;
        }

        fetch(`/orders/${orderId}/data`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.order) {
                    const newStatus = data.order.status;
                    
                    // If status changed to completed, show popup
                    if (newStatus === 'completed' && currentStatus !== 'completed' && !popupShown) {
                        currentStatus = newStatus;
                        showCompletedPopup();
                        updateOrderStatusDisplay(newStatus);
                    } else if (newStatus !== currentStatus) {
                        currentStatus = newStatus;
                        updateOrderStatusDisplay(newStatus);
                    }
                }
            })
            .catch(error => {
                console.error('Error checking order status:', error);
            });
    }

    // Show completed popup
    function showCompletedPopup() {
        popupShown = true;
        
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'popup-overlay';
        overlay.id = 'popupOverlay';
        
        // Create popup
        const popup = document.createElement('div');
        popup.className = 'order-completed-popup';
        popup.id = 'completedPopup';
        popup.innerHTML = `
            <span class="emoji">😊</span>
            <h2>Pesanan Selesai!</h2>
            <p>Pesanan kamu sudah selesai. Terimakasih telah memesan ya 😊</p>
            <button onclick="closeCompletedPopup()">Oke, Terima Kasih</button>
        `;
        
        // Append to body
        document.body.appendChild(overlay);
        document.body.appendChild(popup);
        
        // Auto close after 10 seconds
        setTimeout(() => {
            closeCompletedPopup();
        }, 10000);
    }

    // Close completed popup and redirect to home
    function closeCompletedPopup() {
        const popup = document.getElementById('completedPopup');
        const overlay = document.getElementById('popupOverlay');
        
        if (popup) {
            popup.style.animation = 'popupSlideIn 0.3s ease-out reverse';
            setTimeout(() => popup.remove(), 300);
        }
        
        if (overlay) {
            overlay.style.animation = 'fadeIn 0.3s ease-out reverse';
            setTimeout(() => overlay.remove(), 300);
        }
        
        // Redirect to home page after closing popup
        setTimeout(() => {
            window.location.href = '{{ route("home") }}';
        }, 300);
    }

    // Update order status display
    function updateOrderStatusDisplay(status) {
        const statusBadge = document.getElementById('orderStatusBadge');
        if (!statusBadge) return;
        
        // Remove old status classes
        statusBadge.classList.remove('status-processing', 'status-pending', 'status-completed');
        
        // Add new status class
        statusBadge.classList.add(`status-${status}`);
        
        // Update text
        if (status === 'processing') {
            statusBadge.textContent = 'Sedang Diproses';
        } else if (status === 'pending') {
            statusBadge.textContent = 'Menunggu Konfirmasi';
        } else if (status === 'completed') {
            statusBadge.textContent = 'Selesai';
        }
    }

    // Start checking order status every 3 seconds
    setInterval(checkOrderStatus, 3000);
    
    // Make function global for onclick
    window.closeCompletedPopup = closeCompletedPopup;

    // Update quantity
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = parseInt(this.getAttribute('data-item-id'));
            const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const currentQty = parseInt(input.value);
            input.value = currentQty + 1;
            updateItemTotal(itemId);
        });
    });

    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = parseInt(this.getAttribute('data-item-id'));
            const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const currentQty = parseInt(input.value);
            if (currentQty > 1) {
                input.value = currentQty - 1;
                updateItemTotal(itemId);
            }
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = parseInt(this.getAttribute('data-item-id'));
            removeItem(itemId);
        });
    });

    function updateItemTotal(itemId) {
        const item = orderItems.find(i => i.id === itemId);
        if (!item) return;

        const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
        const qty = parseInt(input.value);
        const itemElement = document.querySelector(`.order-item[data-item-id="${itemId}"]`);
        const totalElement = itemElement.querySelector('.text-right .item-price');
        
        const total = item.price * qty;
        totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        updateSummary();
    }

    function updateSummary() {
        let totalItems = 0;
        let subtotal = 0;

        document.querySelectorAll('.order-item').forEach(itemEl => {
            const itemId = parseInt(itemEl.getAttribute('data-item-id'));
            const item = orderItems.find(i => i.id === itemId);
            if (!item) return;

            const qty = parseInt(itemEl.querySelector('.quantity-input').value);
            totalItems += qty;
            subtotal += item.price * qty;
        });

        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('totalPrice').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    async function removeItem(itemId) {
        const result = await Swal.fire({
            title: 'Hapus Item?',
            text: 'Item ini akan dihapus dari pesanan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/orders/${orderId}/remove-item/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Remove item from DOM
                    document.querySelector(`.order-item[data-item-id="${itemId}"]`).remove();
                    
                    // Remove from orderItems array
                    orderItems = orderItems.filter(i => i.id !== itemId);
                    
                    // Update order data
                    if (data.order) {
                        orderItems = data.order.order_items;
                    }
                    
                    updateSummary();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Item berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal menghapus item'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menghapus item'
                });
            }
        }
    }

    // Save order changes
    document.getElementById('saveOrderBtn').addEventListener('click', async function() {
        const items = [];
        
        document.querySelectorAll('.order-item').forEach(itemEl => {
            const itemId = parseInt(itemEl.getAttribute('data-item-id'));
            const qty = parseInt(itemEl.querySelector('.quantity-input').value);
            items.push({
                id: itemId,
                quantity: qty
            });
        });

        if (items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pesanan tidak boleh kosong. Silakan tambah item atau batalkan pesanan.'
            });
            return;
        }

        try {
            const response = await fetch(`/orders/${orderId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items })
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pesanan berhasil diupdate',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal mengupdate pesanan'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengupdate pesanan'
            });
        }
    });

    // Cancel order
    document.getElementById('cancelOrderBtn').addEventListener('click', async function() {
        const result = await Swal.fire({
            title: 'Batalkan Pesanan?',
            text: 'Pesanan akan dibatalkan dan dihapus. Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pesanan berhasil dibatalkan',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("menu") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal membatalkan pesanan'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat membatalkan pesanan'
                });
            }
        }
    });
</script>
@endpush
@endsection

