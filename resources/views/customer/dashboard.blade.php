@extends('layouts.customer')
@section('title', 'Dashboard')

@section('content')

<div class="customer-grid">

    {{-- Left: Info + Timer --}}
    <div class="left-panel">

        {{-- Balance Card --}}
        <div class="info-card">
            <span class="info-label">Balance</span>
            <span class="info-value">₱{{ number_format(auth()->user()->balance, 2) }}</span>
        </div>

        {{-- Timer Card --}}
        <div class="info-card">
            <span class="info-label">Time Remaining</span>
            <span class="info-value" id="timer">--:--:--</span>
        </div>

        {{-- Station Card --}}
        <div class="info-card">
            <span class="info-label">Station</span>
            <span class="info-value">{{ auth()->user()->station->name ?? 'None' }}</span>
        </div>

    </div>

    {{-- Right: Products --}}
    <div class="right-panel">
        <div class="card">
            <h2>Shop</h2>
            <div class="product-grid">
                @forelse($products as $product)
                    <div class="product-card {{ $product->stock == 0 ? 'out-of-stock' : '' }}">
                        <span class="product-name">{{ $product->name }}</span>
                        <span class="product-price">₱{{ $product->price }}</span>
                        <span class="product-stock">
                            {{ $product->stock == 0 ? 'Out of stock' : 'Stock: ' . $product->stock }}
                        </span>
                        @if($product->stock > 0)
                            <button class="btn-add-cart"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}">
                                Add to Cart
                            </button>
                        @endif
                    </div>
                @empty
                    <p style="color:#94a3b8;">No products available.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- Cart Sidebar --}}
<div class="cart-sidebar" id="cart-sidebar">
    <div class="cart-header">
        <h3>Your Cart</h3>
        <button id="close-cart">✕</button>
    </div>
    <div class="cart-items" id="cart-items">
        <p style="color:#94a3b8; font-size:14px;">Your cart is empty.</p>
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            Total: <span id="cart-total">₱0.00</span>
        </div>
        <button class="btn-primary" id="checkout-btn">Checkout</button>
    </div>
</div>

{{-- Cart Toggle Button --}}
<button class="cart-toggle" id="cart-toggle">
    🛒 Cart <span class="cart-count" id="cart-count">0</span>
</button>

{{-- Confirmation Modal --}}
<div class="modal-overlay" id="modal-overlay">
    <div class="modal">
        <h3>Confirm Order</h3>
        <div id="modal-items"></div>
        <div class="modal-total">
            Total: <span id="modal-total"></span>
        </div>
        <div class="form-group">
            <label>Payment Method</label>
            <select id="payment-method">
                <option value="balance">Balance (deduct now)</option>
                <option value="cash">Cash (pay on delivery)</option>
                <option value="gcash">GCash (pay on delivery)</option>
            </select>
        </div>
        <div class="modal-actions">
            <button class="btn-secondary" id="cancel-order">Cancel</button>
            <button class="btn-primary" id="confirm-order">Place Order</button>
        </div>
    </div>
</div>

{{-- Hidden form to submit order --}}
<form method="POST" action="{{ route('customer.order') }}" id="order-form">
    @csrf
    <input type="hidden" name="items" id="order-items">
    <input type="hidden" name="payment_method" id="order-payment">
</form>

@endsection

@push('scripts')
<script>
    // Timer
    const balance = {{ auth()->user()->balance }};
    const rate = 15; // ₱15 per hour
    let seconds = Math.floor((balance / rate) * 3600);

    function updateTimer() {
        if (seconds <= 0) {
            document.getElementById('timer').textContent = '00:00:00';
            fetch('{{ route("customer.expired") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                window.location.href = '{{ route("customer.locked") }}';
            });
            return;
        }
        seconds--;
        const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
        const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        document.getElementById('timer').textContent = `${h}:${m}:${s}`;
    }

    setInterval(updateTimer, 1000);
    updateTimer();
</script>
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/stock-refresh.js') }}"></script>
@endpush