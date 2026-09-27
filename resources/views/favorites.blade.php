<x-layouts.app>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        :root {
            --brown-primary: #5c3a21;
            --brown-light: #f5f0eb;
        }

        .favorites-page {
            padding: 20px;
        }

        .favorites-title {
            color: var(--brown-primary);
            font-weight: bold;
            font-size: 1.5rem;
        }

        .favorite-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .favorite-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 7px 20px rgba(0,0,0,0.08);
        }

        .favorite-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 15px;
            background: #f5f0eb;
        }

        .favorite-name {
            font-weight: bold;
            font-size: 1.1rem;
            color: #333;
        }

        .favorite-desc {
            color: #777;
            font-size: 0.85rem;
            margin-top: 5px;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .favorite-price {
            color: var(--brown-primary);
            font-weight: bold;
            font-size: 1.1rem;
        }

        .favorite-rating {
            font-size: 0.85rem;
            color: #555;
        }

   .remove-btn {
    width: 42px !important;
    height: 42px !important;
    min-width: 42px !important;
    min-height: 42px !important;

    border-radius: 50% !important;
    border: 1px solid #ddd !important;

    background-color: #fff !important;
    color: #6b3e20 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    cursor: pointer !important;
    padding: 0 !important;

    transition: all 0.3s ease !important;
}

.remove-btn svg {
    width: 18px !important;
    height: 18px !important;
    display: block !important;
}

.remove-btn:hover {
    background-color: #6b3e20 !important;
    color: #fff !important;
    border-color: #6b3e20 !important;
    transform: scale(1.08);
}
        .cart-btn {
            background: var(--brown-primary);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 7px 13px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cart-btn:hover {
            background: #4a2e1b;
            color: #ffffff;
        }

        .empty-favorites {
            text-align: center;
            padding: 80px 20px;
            color: #777;
        }

        .empty-favorites i {
            font-size: 55px;
            color: #d8c8bb;
            margin-bottom: 20px;
        }

        .empty-favorites h4 {
            color: var(--brown-primary);
            font-weight: bold;
        }

        .empty-favorites p {
            font-size: 0.9rem;
        }

    </style>


    <div class="favorites-page" dir="ltr">

        <!-- العنوان -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="favorites-title mb-1">
                    <i class="fas fa-heart me-2"></i>
                    Favorites
                </h3>

                <p class="text-muted small mb-0">
                    Your favorite coffee choices
                </p>

            </div>

            <span id="favoritesCount"
                  class="badge rounded-pill"
                  style="background-color: #5c3a21;">
                0
            </span>

        </div>


        <!-- قائمة المفضلة -->

        <div id="favoritesContainer"
             class="row">

        </div>


        <!-- حالة عدم وجود مفضلة -->

        <div id="emptyFavorites"
             class="empty-favorites"
             style="display: none;">

            <i class="far fa-heart"></i>

            <h4>
                No Favorites Yet
            </h4>

            <p>
                You haven't added any coffee to your favorites.
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


    <script>

        /*
        |--------------------------------------------------------------------------
        | قراءة المفضلة من localStorage
        |--------------------------------------------------------------------------
        */

        function getFavorites() {

            return JSON.parse(
                localStorage.getItem('favoriteCoffees')
            ) || [];

        }


        /*
        |--------------------------------------------------------------------------
        | عرض المفضلة
        |--------------------------------------------------------------------------
        */

        function displayFavorites() {

            let favorites = getFavorites();

            let container =
                document.getElementById('favoritesContainer');

            let empty =
                document.getElementById('emptyFavorites');

            let count =
                document.getElementById('favoritesCount');


            container.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | تحديث العدد
            |--------------------------------------------------------------------------
            */

            count.innerText = favorites.length;


            /*
            |--------------------------------------------------------------------------
            | إذا كانت المفضلة فارغة
            |--------------------------------------------------------------------------
            */

            if (favorites.length === 0) {

                empty.style.display = 'block';

                return;

            }


            empty.style.display = 'none';


            /*
            |--------------------------------------------------------------------------
            | إنشاء بطاقة لكل قهوة
            |--------------------------------------------------------------------------
            */

            favorites.forEach(function(coffee) {

                let col =
                    document.createElement('div');

                col.className =
                    'col-lg-6 col-xl-4';


                col.innerHTML = `

                    <div class="favorite-card">

                        <div class="d-flex align-items-start">

                            <!-- صورة القهوة -->

                            <img
                                src="${coffee.image || 'https://via.placeholder.com/90?text=Coffee'}"
                                class="favorite-img me-3"
                                alt="Coffee"
                                onerror="this.src='https://via.placeholder.com/90?text=Coffee'"
                            >


                            <!-- معلومات القهوة -->

                            <div class="flex-grow-1">

                                <div class="favorite-name">

                                    ${escapeHtml(coffee.name)}

                                </div>


                                <div class="favorite-desc">

                                    ${escapeHtml(
                                        coffee.description || 'No description'
                                    )}

                                </div>


                                <!-- التقييم -->

                                <div class="favorite-rating mt-2">

                                    <i class="fas fa-star text-warning"></i>

                                    ${escapeHtml(
                                        coffee.rating || '0.0'
                                    )}

                                </div>


                                <!-- السعر -->

                                <div class="favorite-price mt-1">

                                    $${escapeHtml(
                                        coffee.price || '0'
                                    )}

                                </div>

                            </div>


                            <!-- زر الحذف -->

                          <button 
    type="button" 
    class="remove-btn" 
    onclick="removeFavorite('${coffee.id}')" 
    title="Remove from favorites">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="18"
         height="18"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">

        <polyline points="3 6 5 6 21 6"></polyline>
        <path d="M19 6l-1 14H6L5 6"></path>
        <path d="M10 11v6"></path>
        <path d="M14 11v6"></path>
        <path d="M9 6V4h6v2"></path>

    </svg>

</button>
                        </div>


                        <!-- الأزرار -->

                        <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">

                            <button
                                type="button"
                                class="cart-btn"
                                onclick="addFavoriteToCart('${coffee.id}')">

                                <i class="fas fa-shopping-cart me-1"></i>

                                Add to Cart

                            </button>

                        </div>

                    </div>

                `;


                container.appendChild(col);

            });

        }


        /*
        |--------------------------------------------------------------------------
        | حذف القهوة من Favorites
        |--------------------------------------------------------------------------
        */

        function removeFavorite(id) {

            let favorites = getFavorites();


            favorites =
                favorites.filter(function(coffee) {

                    return String(coffee.id) !== String(id);

                });


            localStorage.setItem(
                'favoriteCoffees',
                JSON.stringify(favorites)
            );


            displayFavorites();

        }

/*
        |--------------------------------------------------------------------------
        | إضافة القهوة المفضلة إلى Cart وتحديث الصفحة
        |--------------------------------------------------------------------------
        */

        function addFavoriteToCart(id) {

            let favorites =
                getFavorites();


            let coffee =
                favorites.find(function(item) {

                    return String(item.id) === String(id);

                });


            if (!coffee) {

                return;

            }


            let cart =
                JSON.parse(
                    localStorage.getItem('cartItems')
                ) || [];


            let existing =
                cart.find(function(item) {

                    return String(item.id) === String(id);

                });


            /*
            |--------------------------------------------------------------------------
            | إذا كانت موجودة في السلة
            |--------------------------------------------------------------------------
            */

            if (existing) {

                existing.quantity =
                    (Number(existing.quantity) || 1) + 1;

            }

            /*
            |--------------------------------------------------------------------------
            | إذا لم تكن موجودة
            |--------------------------------------------------------------------------
            */

            else {

                cart.push({

                    id: coffee.id,

                    name: coffee.name,

                    price: coffee.price,

                    image: coffee.image,

                    quantity: 1

                });

            }


            localStorage.setItem(
                'cartItems',
                JSON.stringify(cart)
            );


            /*
            |--------------------------------------------------------------------------
            | رسالة تأكيد لطيفة والانتقال لصفحة السلة اختيارياً أو إبقاء المستخدم مكانه
            |--------------------------------------------------------------------------
            */

            alert(
                coffee.name + ' added to cart successfully!'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | حماية النصوص
        |--------------------------------------------------------------------------
        */

        function escapeHtml(text) {

            if (text === null ||
                text === undefined) {

                return '';

            }


            return String(text)

                .replace(/&/g, '&amp;')

                .replace(/</g, '&lt;')

                .replace(/>/g, '&gt;')

                .replace(/"/g, '&quot;')

                .replace(/'/g, '&#039;');

        }


        /*
        |--------------------------------------------------------------------------
        | تشغيل الصفحة
        |--------------------------------------------------------------------------
        */

        document.addEventListener('DOMContentLoaded', function () {
    displayFavorites();
});

document.addEventListener('livewire:navigated', function () {
    displayFavorites();
});
        

    </script>


</x-layouts.app>

