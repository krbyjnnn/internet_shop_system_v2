let cart = [];

// Add to cart
document.querySelectorAll('.btn-add-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const price = parseFloat(this.dataset.price);

        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }

        updateCart();
        openCart();
    });
});

function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    cartCount.textContent = cart.reduce((sum, i) => sum + i.quantity, 0);

    if (cart.length === 0) {
        cartItems.innerHTML = '<p style="color:#94a3b8; font-size:14px;">Your cart is empty.</p>';
        cartTotal.textContent = '₱0.00';
        return;
    }

    let total = 0;
    cartItems.innerHTML = '';

    cart.forEach(item => {
        total += item.price * item.quantity;
        cartItems.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">₱${item.price} x ${item.quantity}</div>
                </div>
                <div class="cart-item-controls">
                    <button onclick="changeQty('${item.id}', -1)">−</button>
                    <span>${item.quantity}</span>
                    <button onclick="changeQty('${item.id}', 1)">+</button>
                    <button onclick="removeItem('${item.id}')" style="background-color:#7f1d1d; color:#fca5a5;">✕</button>
                </div>
            </div>
        `;
    });

    cartTotal.textContent = `₱${total.toFixed(2)}`;
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.quantity += delta;
    if (item.quantity <= 0) removeItem(id);
    else updateCart();
}

function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    updateCart();
}

function openCart() {
    document.getElementById('cart-sidebar').classList.add('open');
}

// Cart toggle
document.getElementById('cart-toggle').addEventListener('click', openCart);
document.getElementById('close-cart').addEventListener('click', () => {
    document.getElementById('cart-sidebar').classList.remove('open');
});

// Checkout
document.getElementById('checkout-btn').addEventListener('click', () => {
    if (cart.length === 0) return;

    const modal = document.getElementById('modal-overlay');
    const modalItems = document.getElementById('modal-items');
    const modalTotal = document.getElementById('modal-total');

    let total = 0;
    modalItems.innerHTML = '';

    cart.forEach(item => {
        total += item.price * item.quantity;
        modalItems.innerHTML += `<p style="font-size:14px; color:#94a3b8; margin-bottom:6px;">
            ${item.name} x${item.quantity} = ₱${(item.price * item.quantity).toFixed(2)}
        </p>`;
    });

    modalTotal.textContent = `₱${total.toFixed(2)}`;
    modal.classList.add('open');
});

// Cancel order
document.getElementById('cancel-order').addEventListener('click', () => {
    document.getElementById('modal-overlay').classList.remove('open');
});

// Confirm order
document.getElementById('confirm-order').addEventListener('click', () => {
    document.getElementById('order-items').value = JSON.stringify(cart);
    document.getElementById('order-payment').value = document.getElementById('payment-method').value;
    document.getElementById('order-form').submit();
});