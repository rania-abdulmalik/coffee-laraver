<x-layouts.app>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brown-primary: #5c3a21;
            --brown-light: #f5f0eb;
        }

        .cart-page {
            padding: 20px;
        }

        .cart-title {
            color: var(--brown-primary);
            font-weight: bold;
            font-size: 1.5rem;
        }

        .cart-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .cart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0,0,0,0.08);
        }

        .cart-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 15px;
            background: #f5f0eb;
        }

        .cart-name {
            font-weight: bold;
            font-size: 1.1rem;
            color: #333;
        }

        .cart-price {
            color: var(--brown-primary);
            font-weight: bold;
            font-size: 1rem;
        }

        .quantity-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: #fff;
            color: var(--brown-primary);
            font-weight: bold;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background: var(--brown-light);
        }

        .quantity-number {
            min-width: 25px;
            text-align: center;
            font-weight: bold;
        }

        .remove-cart-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid #e0e0e0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
        }

        .remove-cart-btn:hover {
            background: #fff1f1;
            border-color: #dc3545;
        }

        .empty-cart {
            text-align: center;
            padding: 80px 20px;
            color: #777;
        }

        .empty-cart i {
            font-size: 60px;
            color: #d8c8bb;
            margin-bottom: 20px;
        }

        .empty-cart h4 {
            color: var(--brown-primary);
            font-weight: bold;
        }

        .empty-cart p {
            font-size: 0.9rem;
        }
    </style>

    <div class="cart-page" dir="ltr">

        <!-- العنوان -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="cart-title mb-1">
                    <i class="fas fa-shopping-cart me-2"></i>
                    My Cart
                </h3>
                <p class="text-muted small mb-0">
                    Your selected coffee
                </p>
            </div>

            <span id="cartCount"
                  class="badge rounded-pill"
                  style="background-color:#5c3a21;">
                0
            </span>
        </div>

        <!-- محتوى السلة -->
        <div class="row">
            <div class="col-12">
                <div id="cartContainer"></div>

                <!-- السلة فارغة -->
                <div id="emptyCart"
                     class="empty-cart"
                     style="display:none;">
                    <i class="fas fa-shopping-cart"></i>
                    <h4>
                        Your Cart is Empty
                    </h4>
                    <p>
                        You haven't added any coffee to your cart yet.
                    </p>

                    <a href="{{ route('dashboard') }}"
                       class="btn"
                       style="
                            background-color:#5c3a21;
                            color:white;
                            border-radius:10px;
                            padding:8px 18px;
                       "
                       wire:navigate>
                        <i class="fas fa-coffee me-1"></i>
                        Browse Coffee Menu
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function getCart() {
            return JSON.parse(localStorage.getItem('cartItems')) || [];
        }

        function displayCart() {
            let cart = getCart();
            let container = document.getElementById('cartContainer');
            let empty = document.getElementById('emptyCart');
            let count = document.getElementById('cartCount');

            if (!container || !empty || !count) return;

            container.innerHTML = '';

            let totalQuantity = 0;
            cart.forEach(function(item) {
                let quantity = Number(item.quantity) || 1;
                totalQuantity += quantity;
            });

            count.innerText = totalQuantity;

            if (cart.length === 0) {
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';

            cart.forEach(function(item) {
                let col = document.createElement('div');
                col.className = 'cart-card';

                let quantity = Number(item.quantity) || 1;
                let price = Number(item.price) || 0;
                let itemTotal = price * quantity;

                col.innerHTML = `
                    <div class="d-flex align-items-center">
                        <img src="${item.image || 'https://via.placeholder.com/90?text=Coffee'}"
                             class="cart-img me-3"
                             alt="Coffee"
                             onerror="this.src='https://via.placeholder.com/90?text=Coffee'">
                        <div class="flex-grow-1">
                            <div class="cart-name">${escapeHtml(item.name)}</div>
                            <div class="cart-price mt-1">$${price.toFixed(2)}</div>
                            <div class="small text-muted mt-1">Total: $${itemTotal.toFixed(2)}</div>
                        </div>
                        <div class="quantity-box me-3">
                            <button type="button" class="quantity-btn" onclick="changeQuantity('${item.id}', -1)">−</button>
                            <span class="quantity-number">${quantity}</span>
                            <button type="button" class="quantity-btn" onclick="changeQuantity('${item.id}', 1)">+</button>
                        </div>
                        <button type="button" class="remove-cart-btn" onclick="removeFromCart('${item.id}')" title="Remove">
                            🗑️
                        </button>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        function changeQuantity(id, change) {
            let cart = getCart();
            let item = cart.find(product => String(product.id) === String(id));
            if (!item) return;

            item.quantity = (Number(item.quantity) || 1) + change;

            if (item.quantity <= 0) {
                cart = cart.filter(product => String(product.id) !== String(id));
            }

            localStorage.setItem('cartItems', JSON.stringify(cart));
            displayCart();
        }

        function removeFromCart(id) {
            let cart = getCart();
            cart = cart.filter(item => String(item.id) !== String(id));
            localStorage.setItem('cartItems', JSON.stringify(cart));
            displayCart();
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        document.addEventListener('DOMContentLoaded', displayCart);
        document.addEventListener('livewire:navigated', displayCart);
    </script>

</x-layouts.app>