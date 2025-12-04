// Function to show toast notification
function showNotification(message) {
    // Remove existing notification if any
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create notification element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <i class="ri-information-line"></i>
        <span>${message}</span>
    `;
    
    // Add to body
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Function to filter menu items by category
function filterMenuItems(category) {
    const menuItems = document.querySelectorAll('.menu-item-card');
    menuItems.forEach(item => {
        if (category === 'all') {
            item.classList.remove('hidden');
        } else {
            if (item.dataset.category === category) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        }
    });
}

// Initialize page with "makanan" category active
document.addEventListener('DOMContentLoaded', () => {
    // Filter to show makanan items on page load
    filterMenuItems('makanan');
});

// Cart functionality
let cart = [];

function addToCart(name, price) {
    // Find the menu item to get its category and image
    const menuItem = Array.from(document.querySelectorAll('.menu-item-card')).find(card => {
        const btn = card.querySelector('.add-to-cart-btn');
        return btn && btn.getAttribute('data-name') === name;
    });
    
    const category = menuItem ? menuItem.getAttribute('data-category') : 'all';
    
    // Get image source
    let imageSrc = '';
    if (menuItem) {
        const img = menuItem.querySelector('.menu-item-image');
        if (img) {
            imageSrc = img.src;
        }
    }
    
    // Check if item already exists in cart
    const existingItem = cart.find(item => item.name === name && item.price === price);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: name,
            price: price,
            quantity: 1,
            category: category,
            image: imageSrc
        });
    }
    
    updateOrderBar();
    
    // Check if panel is open and keep it open, then update it
    const orderPanel = document.getElementById('orderPanel');
    const isPanelOpen = orderPanel && orderPanel.classList.contains('active');
    
    if (isPanelOpen) {
        // Panel is open, keep it open and update it
        updateOrderPanel();
    }
    // If panel is not open, don't do anything (user can open it manually)
}

function updateOrderBar() {
    const bottomOrderBar = document.getElementById('bottomOrderBar');
    const orderCount = document.getElementById('orderCount');
    const orderTotal = document.getElementById('orderTotal');
    
    if (cart.length === 0) {
        bottomOrderBar.style.display = 'none';
        return;
    }
    
    bottomOrderBar.style.display = 'flex';
    
    // Calculate total items
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    orderCount.textContent = `${totalItems} Pesanan`;
    
    // Calculate total price
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    orderTotal.textContent = totalPrice.toLocaleString('id-ID');
}

function openOrderPanel() {
    const orderPanel = document.getElementById('orderPanel');
    const orderPanelOverlay = document.getElementById('orderPanelOverlay');
    const menuSection = document.getElementById('menuSection');
    const menuGrid = document.getElementById('menuItemsGrid');
    const bottomOrderBar = document.getElementById('bottomOrderBar');
    
    // Hide bottom order bar when panel opens
    if (bottomOrderBar) {
        bottomOrderBar.style.display = 'none';
    }
    
    orderPanel.classList.add('active');
    orderPanelOverlay.classList.add('active');
    menuSection.classList.add('panel-open');
    menuGrid.classList.add('panel-open');
    updateOrderPanel();
}

function closeOrderPanel() {
    const orderPanel = document.getElementById('orderPanel');
    const orderPanelOverlay = document.getElementById('orderPanelOverlay');
    const menuSection = document.getElementById('menuSection');
    const menuGrid = document.getElementById('menuItemsGrid');
    const bottomOrderBar = document.getElementById('bottomOrderBar');
    
    orderPanel.classList.remove('active');
    orderPanelOverlay.classList.remove('active');
    menuSection.classList.remove('panel-open');
    menuGrid.classList.remove('panel-open');
    
    // Show bottom order bar again if there are items in cart
    if (bottomOrderBar && cart.length > 0) {
        updateOrderBar();
    }
}

function updateOrderPanel() {
    const orderItemsList = document.getElementById('orderItemsList');
    const orderSummaryItems = document.getElementById('orderSummaryItems');
    const orderTotalPrice = document.getElementById('orderTotalPrice');
    const checkoutBtn = document.getElementById('checkoutBtn');

    // Clear existing items
    orderItemsList.innerHTML = '';
    orderSummaryItems.innerHTML = '';

    if (cart.length === 0) {
        orderItemsList.innerHTML = '<p style="color: #888; text-align: center; padding: 2rem;">Keranjang masih kosong</p>';
        orderTotalPrice.textContent = 'Rp0';
        checkoutBtn.disabled = true;
        return;
    }

    checkoutBtn.disabled = false;

    // Calculate total price
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    orderTotalPrice.textContent = `Rp${totalPrice.toLocaleString('id-ID')}`;

    // Render order items
    cart.forEach((item, index) => {
        // Use stored image or try to get from menu item as fallback
        let imageSrc = item.image || '';
        if (!imageSrc) {
            const menuItem = Array.from(document.querySelectorAll('.menu-item-card')).find(card => {
                const btn = card.querySelector('.add-to-cart-btn');
                return btn && btn.getAttribute('data-name') === item.name;
            });
            
            if (menuItem) {
                const img = menuItem.querySelector('.menu-item-image');
                if (img) {
                    imageSrc = img.src;
                    // Update cart item with image for future use
                    item.image = imageSrc;
                }
            }
        }

        // Create order item card
        const itemCard = document.createElement('div');
        itemCard.className = 'order-item-card';
        itemCard.setAttribute('data-category', item.category || 'all');
        itemCard.innerHTML = `
            <img src="${imageSrc}" alt="${item.name}" class="order-item-image" onerror="this.style.display='none'">
            <div class="order-item-details">
                <h4 class="order-item-name">
                    ${item.name}
                    <i class="ri-shield-check-line" style="font-size: 1rem; color: #10b981;"></i>
                </h4>
                <p class="order-item-price">Rp${item.price.toLocaleString('id-ID')}</p>
                <div class="order-item-quantity">
                    <button class="quantity-btn" onclick="decreaseQuantity(${index})">-</button>
                    <span class="quantity-value">${String(item.quantity).padStart(2, '0')}</span>
                    <button class="quantity-btn" onclick="increaseQuantity(${index})">+</button>
                </div>
            </div>
        `;
        orderItemsList.appendChild(itemCard);

        // Add to summary
        const summaryItem = document.createElement('div');
        summaryItem.className = 'summary-item';
        summaryItem.innerHTML = `
            <span>${item.quantity} x ${item.name}</span>
            <span>Rp${(item.price * item.quantity).toLocaleString('id-ID')}</span>
        `;
        orderSummaryItems.appendChild(summaryItem);
    });
}

window.increaseQuantity = function(index) {
    cart[index].quantity += 1;
    updateOrderBar();
    updateOrderPanel();
};

window.decreaseQuantity = function(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1;
    } else {
        cart.splice(index, 1);
    }
    updateOrderBar();
    updateOrderPanel();
};

function filterOrderItems(category) {
    const orderItems = document.querySelectorAll('.order-item-card');
    orderItems.forEach(item => {
        if (category === 'all') {
            item.style.display = 'flex';
        } else {
            const itemCategory = item.getAttribute('data-category');
            if (itemCategory === category) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        }
    });
}

// Add event listeners after DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Add event listeners to all "Tambah" buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            const name = this.getAttribute('data-name');
            const price = parseInt(this.getAttribute('data-price'));
            
            // Check if panel is open before adding to cart
            const orderPanel = document.getElementById('orderPanel');
            const wasPanelOpen = orderPanel && orderPanel.classList.contains('active');
            
            addToCart(name, price);
            
            // If panel was open, ensure it stays open
            if (wasPanelOpen && orderPanel) {
                if (!orderPanel.classList.contains('active')) {
                    openOrderPanel();
                } else {
                    updateOrderPanel();
                }
            }
        });
    });

    // View order button functionality
    const viewOrderBtn = document.getElementById('viewOrderBtn');
    if (viewOrderBtn) {
        viewOrderBtn.addEventListener('click', function() {
            if (cart.length === 0) {
                showNotification('tambah menu dulu yuk');
                return;
            }
            openOrderPanel();
        });
    }

    // Close order panel button
    const closeOrderBtn = document.getElementById('closeOrderBtn');
    if (closeOrderBtn) {
        closeOrderBtn.addEventListener('click', function() {
            closeOrderPanel();
        });
    }

    // Close order panel when clicking overlay
    const orderPanelOverlay = document.getElementById('orderPanelOverlay');
    if (orderPanelOverlay) {
        orderPanelOverlay.addEventListener('click', function() {
            closeOrderPanel();
        });
    }

    // Bottom order bar click functionality
    const bottomOrderBar = document.getElementById('bottomOrderBar');
    if (bottomOrderBar) {
        bottomOrderBar.addEventListener('click', function(e) {
            // Don't trigger if clicking the close button
            if (e.target.closest('.close-bottom-bar-btn')) {
                return;
            }
            
            if (cart.length === 0) return;
            
            const orderPanel = document.getElementById('orderPanel');
            // Open order panel when bottom bar is clicked
            if (orderPanel && !orderPanel.classList.contains('active')) {
                openOrderPanel();
            } else if (orderPanel && orderPanel.classList.contains('active')) {
                // If already open, close it
                closeOrderPanel();
            }
        });
    }

    // Close bottom bar button functionality
    const closeBottomBarBtn = document.getElementById('closeBottomBarBtn');
    if (closeBottomBarBtn) {
        closeBottomBarBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering bottom bar click
            
            // Clear cart
            cart = [];
            
            // Hide bottom order bar
            if (bottomOrderBar) {
                bottomOrderBar.style.display = 'none';
            }
            
            // Close order panel if open
            const orderPanel = document.getElementById('orderPanel');
            if (orderPanel && orderPanel.classList.contains('active')) {
                closeOrderPanel();
            }
            
            // Update order panel to show empty state
            updateOrderPanel();
        });
    }

    // Order panel category filter
    document.querySelectorAll('.order-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.order-category-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const category = this.getAttribute('data-order-category');
            filterOrderItems(category);
        });
    });

    // Payment option buttons
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Checkout button
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong');
                return;
            }
            
            const selectedPayment = document.querySelector('.payment-btn.active')?.getAttribute('data-payment') || 'cash';
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            // Format order message
            let orderMessage = 'Tambahan dari Class Billiard Eatery\n\n';
            cart.forEach(item => {
                orderMessage += `${item.quantity}x ${item.name} - Rp${(item.price * item.quantity).toLocaleString('id-ID')}\n`;
            });
            orderMessage += `\nTotal: Rp${totalPrice.toLocaleString('id-ID')}\n`;
            orderMessage += `Metode Pembayaran: ${selectedPayment.toUpperCase()}`;
            
            // Send to WhatsApp (you can customize the phone number)
            const phoneNumber = '6281234567890'; // Replace with actual WhatsApp number
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(orderMessage)}`;
            window.open(whatsappUrl, '_blank');
        });
    }
});

// Category filter functionality
document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove active class from all buttons
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        // Add active class to clicked button
        btn.classList.add('active');
        
        const category = btn.dataset.category;
        filterMenuItems(category);
    });
});

