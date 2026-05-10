// Refresh stock every 30 seconds
setInterval(() => {
    fetch(window.location.href)
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.querySelector('.product-grid');
            const currentGrid = document.querySelector('.product-grid');
            if (newGrid && currentGrid) {
                currentGrid.innerHTML = newGrid.innerHTML;
                // Re-attach cart listeners after refresh
                attachCartListeners();
            }
        });
}, 30000);

function attachCartListeners() {
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
}