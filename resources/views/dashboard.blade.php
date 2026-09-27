<x-layouts.app>
    <!-- تضمين الخطوط ومكتبة Bootstrap والأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brown-primary: #5c3a21;
            --brown-light: #f5f0eb;
            --gold-star: #ffc107;
        }

        .coffee-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            position: relative;
        }

        .coffee-card:hover {
            transform: translateY(-5px);
        }

        .coffee-img {
            width: 85px;
            height: 85px;
            border-radius: 15px;
            object-fit: cover;
        }

        .coffee-title {
            font-weight: bold;
            font-size: 1.1rem;
            color: #333;
        }

        .coffee-desc {
            font-size: 0.8rem;
            color: #777;
            margin-top: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .price-tag {
            font-weight: bold;
            color: #5c3a21;
            font-size: 1.1rem;
        }

        .btn-brown {
            background-color: var(--brown-primary);
            color: white;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .btn-brown:hover {
            background-color: #4a2e1b;
            color: white;
        }

        /* دائرة الثلاث نقاط في أعلى اليمين تماماً */
        .card-menu-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #333 !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .card-menu-btn:hover {
            background: #f8f9fa;
        }

        /* دائرة زر القلب الأنيقة في الأسفل بجانب التفاصيل */
        .card-fav-circle {
            width: 35px;
            height: 35px;
            border: 1px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .card-fav-circle:hover {
            background: #f8f9fa;
        }

        .heart-btn {
            background: none;
            border: none;
            color: #ccc;
            font-size: 1rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .heart-btn.active {
            color: #dc3545 !important;
        }

        .dropdown-toggle::after {
            display: none;
        }

        /* إظهار الثلاث نقاط والقلب حتى لو لم يتم تحميل Font Awesome */
        .menu-dots {
            font-size: 22px;
            line-height: 1;
            font-weight: bold;
        }

        .heart-symbol {
            font-size: 18px;
            line-height: 1;
            color: #ccc;
        }

        .heart-btn.active .heart-symbol {
            color: #dc3545 !important;
        }

        /* زر الإضافة إلى السلة بجانب التفاصيل */
        .cart-btn {
            background: var(--brown-primary);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cart-btn:hover {
            background: #4a2e1b;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .cart-btn.added {
            background: #198754;
        }
    </style>

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4" dir="ltr">

        <!-- الترويسة وشريط البحث وزر الإضافة -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0" style="color: var(--brown-primary);">
                ☕ Coffee Menu
            </h3>

            <!-- تم تجميع البحث والزر هنا -->
            <div class="d-flex align-items-center gap-3">
                <!-- نموذج البحث -->
                <form action="{{ url()->current() }}" method="GET" class="mb-0">
                    <div class="position-relative">
                        <input type="text" 
                               name="search" 
                               class="form-control bg-white" 
                               placeholder="Search coffee..." 
                               value="{{ request('search') }}"
                               style="border-radius: 20px; padding: 8px 35px 8px 15px; width: 250px; border: 1px solid #e0e0e0; outline: none;">
                        <button type="submit" class="position-absolute border-0 bg-transparent" style="right: 12px; top: 50%; transform: translateY(-50%); color: #777;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <button class="btn btn-brown px-4 py-2"
                        data-bs-toggle="modal"
                        data-bs-target="#addCoffeeModal">
                    <i class="fas fa-plus me-1"></i> Add Coffee
                </button>
            </div>
        </div>

        <!-- قائمة القهوة -->
        <div class="row">

            @forelse($products as $product)

            <div class="col-lg-6 col-xl-4">

                <div class="coffee-card">

                    <!-- دائرة الثلاث نقاط في أعلى اليمين -->
                    <div class="dropdown">

                        <a href="#"
                           class="card-menu-btn dropdown-toggle"
                           data-bs-toggle="dropdown">

                            <span class="menu-dots">⋮</span>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                            <li>

                                <button class="dropdown-item"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-cat="{{ $product->category_id }}"
                                    data-desc="{{ $product->description }}"
                                    data-price="{{ $product->price }}"
                                    data-rating="{{ $product->rating }}"
                                    data-beans="{{ $product->beans }}"
                                    data-serving="{{ $product->serving }}"
                                    data-cup="{{ $product->cup_size }}"
                                    onclick="openEditModal(this)">

                                    <i class="fas fa-pen text-warning me-2"></i>
                                    Edit

                                </button>

                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <button class="dropdown-item text-danger"
                                    data-id="{{ $product->id }}"
                                    onclick="deleteCoffee(this.getAttribute('data-id'))">

                                    <i class="fas fa-trash me-2"></i>
                                    Delete

                                </button>

                            </li>

                        </ul>

                    </div>

                    <!-- محتوى البطاقة -->
                    <div class="d-flex align-items-start pe-4">

                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="coffee-img me-3"
                             alt="Coffee"
                             onerror="this.src='https://via.placeholder.com/85?text=No+Image'">

                        <div class="flex-grow-1 pe-2">

                            <div class="coffee-title">
                                {{ $product->name }}
                            </div>

                            <div class="coffee-desc">
                                {{ $product->description }}
                            </div>

                        </div>

                    </div>

                    <!-- السعر والتقييم -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">

                        <div class="d-flex align-items-center">

                            <i class="fas fa-star text-warning me-1"></i>

                            <span class="fw-bold small">
                                {{ $product->rating ?? '0.0' }}
                            </span>

                        </div>

                        <div class="price-tag">
                            ${{ $product->price }}
                        </div>

                    </div>

                    <!-- أزرار التفاصيل والسلة والقلب -->
                    <div class="d-flex justify-content-between align-items-center mt-3">

                        <div class="d-flex align-items-center gap-2">

                            <!-- زر التفاصيل -->
                            <button class="btn btn-brown px-3 py-1"
                                data-name="{{ $product->name }}"
                                data-desc="{{ $product->description }}"
                                data-price="{{ $product->price }}"
                                data-rating="{{ $product->rating }}"
                                data-beans="{{ $product->beans }}"
                                data-serving="{{ $product->serving }}"
                                data-cup="{{ $product->cup_size }}"
                                data-origin="{{ $product->origin ?? 'N/A' }}"
                                onclick="showDetailsFromButton(this)">

                                Details

                            </button>

                            <!-- زر إضافة القهوة إلى السلة -->
                            <button type="button"
                                class="cart-btn"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ asset('storage/' . $product->image) }}"
                                onclick="addToCart(this)">

                                <span style="font-size: 18px;">🛒</span>
                                Add Cart

                            </button>

                        </div>

                        <!-- دائرة زر القلب -->
                        <div class="card-fav-circle">

                            <button type="button"
                                class="heart-btn"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-desc="{{ $product->description }}"
                                data-price="{{ $product->price }}"
                                data-rating="{{ $product->rating ?? '0.0' }}"
                                data-image="{{ asset('storage/' . $product->image) }}"
                                onclick="toggleFavorite(this)">

                                <span class="heart-symbol">♥</span>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center text-muted my-5">

                <h5 class="fw-bold">
                    The menu is empty. Start by adding your first coffee!
                </h5>

            </div>

            @endforelse

        </div>

    </div>


    <!-- نافذة تفاصيل القهوة -->
    <div class="modal fade"
         id="coffeeDetailsModal"
         tabindex="-1"
         dir="ltr">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content"
                 style="border-radius: 15px;">

                <div class="modal-header"
                     style="background-color: var(--brown-primary); color: white;">

                    <h5 class="modal-title"
                        id="modalCoffeeName">

                        Coffee Details

                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <p>
                        <strong>Description:</strong>
                        <span id="modalDesc"></span>
                    </p>

                    <p>
                        <strong>Price:</strong>
                        $<span id="modalPrice"></span>
                    </p>

                    <p>
                        <strong>Rating:</strong>
                        ⭐ <span id="modalRating"></span>
                    </p>

                    <p>
                        <strong>Beans:</strong>
                        <span id="modalBeans"></span>
                    </p>

                    <p>
                        <strong>Serving:</strong>
                        <span id="modalServing"></span>
                    </p>

                    <p>
                        <strong>Cup Size:</strong>
                        <span id="modalCupSize"></span>
                    </p>

                    <p>
                        <strong>Origin:</strong>
                        <span id="modalOrigin"></span>
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- نافذة إضافة قهوة -->
    <div class="modal fade"
         id="addCoffeeModal"
         tabindex="-1"
         dir="ltr">

        <div class="modal-dialog">

            <div class="modal-content"
                 style="border-radius: 15px;">

                <div class="modal-header"
                     style="background-color: var(--brown-primary); color: white;">

                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>
                        Add Coffee
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <form id="addCoffeeForm"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">
                            <input type="text"
                                   name="name"
                                   class="form-control bg-light border-0"
                                   placeholder="Name"
                                   required
                                   style="border-radius: 10px;">
                        </div>

                        <div class="mb-3">

                            <select name="category_id"
                                    class="form-control bg-light border-0"
                                    required
                                    style="border-radius: 10px;">

                                <option value=""
                                        disabled
                                        selected>
                                    Category...
                                </option>

                                @foreach($categories as $category)

                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">

                            <textarea name="description"
                                      class="form-control bg-light border-0"
                                      placeholder="Description"
                                      rows="2"
                                      style="border-radius: 10px;">
                            </textarea>

                        </div>

                        <div class="mb-3">

                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   class="form-control bg-light border-0"
                                   placeholder="Price"
                                   required
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <input type="number"
                                   step="0.1"
                                   name="rating"
                                   class="form-control bg-light border-0"
                                   placeholder="Rating"
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <input type="file"
                                   name="image"
                                   class="form-control bg-light border-0"
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <input type="text"
                                   name="beans"
                                   class="form-control bg-light border-0"
                                   placeholder="Beans"
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <select name="serving"
                                    class="form-control bg-light border-0"
                                    required
                                    style="border-radius: 10px;">

                                <option value=""
                                        disabled
                                        selected>
                                    Serving (Hot/Cold)...
                                </option>

                                <option value="Hot">Hot</option>
                                <option value="Cold">Cold</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <select name="cup_size"
                                    class="form-control bg-light border-0"
                                    required
                                    style="border-radius: 10px;">

                                <option value=""
                                        disabled
                                        selected>
                                    Cup Size...
                                </option>

                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>

                            </select>

                        </div>


                         <div class="mb-3">
                            <input type="text"
                             id="origin"
                              name="origin" 
                              placeholder="Origin" 
                               class="form-control bg-light border-0"
                               style="border-radius: 10px;">

                         </div> 

                        <button type="submit"
                                class="btn btn-brown w-100 py-2 mt-3">

                            Save Coffee

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <!-- نافذة تعديل القهوة -->
    <div class="modal fade"
         id="editCoffeeModal"
         tabindex="-1"
         dir="ltr">

        <div class="modal-dialog">

            <div class="modal-content"
                 style="border-radius: 15px;">

                <div class="modal-header"
                     style="background-color: var(--brown-primary); color: white;">

                    <h5 class="modal-title">
                        <i class="fas fa-pen me-2"></i>
                        Edit Coffee
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <form id="editCoffeeForm"
                          enctype="multipart/form-data">

                        @csrf

                        <input type="hidden"
                               id="edit_id"
                               name="id">

                        <div class="mb-3">

                            <input type="text"
                                   id="edit_name"
                                   name="name"
                                   class="form-control bg-light border-0"
                                   placeholder="Name"
                                   required
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <select id="edit_category_id"
                                    name="category_id"
                                    class="form-control bg-light border-0"
                                    required
                                    style="border-radius: 10px;">

                                @foreach($categories as $category)

                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">

                            <textarea id="edit_description"
                                      name="description"
                                      class="form-control bg-light border-0"
                                      placeholder="Description"
                                      rows="2"
                                      style="border-radius: 10px;">
                            </textarea>

                        </div>

                        <div class="mb-3">

                            <input type="number"
                                   step="0.01"
                                   id="edit_price"
                                   name="price"
                                   class="form-control bg-light border-0"
                                   placeholder="Price"
                                   required
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <input type="number"
                                   step="0.1"
                                   id="edit_rating"
                                   name="rating"
                                   class="form-control bg-light border-0"
                                   placeholder="Rating"
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <input type="file"
                                   name="image"
                                   class="form-control bg-light border-0"
                                   style="border-radius: 10px;">

                        </div>

                        <div class="mb-3">

                            <input type="text"
                                   id="edit_beans"
                                   name="beans"
                                   class="form-control bg-light border-0"
                                   placeholder="Beans"
                                   style="border-radius: 10px;">

                        </div>
   


                        <div class="mb-3">

                            <select id="edit_serving"
                                    name="serving"
                                    class="form-control bg-light border-0"
                                    required
                                    style="border-radius: 10px;">

                                <option value="Hot">Hot</option>
                                <option value="Cold">Cold</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <select id="edit_cup_size"
                                    name="cup_size"
                                    class="form-control bg-light border-0"
                                    required
                                    style="border-radius: 10px;">

                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>

                            </select>

                        </div>

                        
                         <div class="mb-3">
                            <input type="text"
                             id="edit_origin"
                              name="origin" 
                              placeholder="Origin" 
                               class="form-control  bg-light border-0"
                               style="border-radius: 10px;">

                         </div> 

                        <button type="submit"
                                class="btn btn-brown w-100 py-2 mt-3">

                            Update Coffee

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    // إضافة وحذف القهوة من المفضلة
    function toggleFavorite(button) {

        let coffeeId = button.getAttribute('data-id');

        let favorites =
            JSON.parse(localStorage.getItem('favoriteCoffees')) || [];

        if (button.classList.contains('active')) {

            button.classList.remove('active');

            favorites = favorites.filter(function(coffee) {

                return String(coffee.id) !== String(coffeeId);

            });

        } else {

            button.classList.add('active');

            let coffee = {

                id: coffeeId,

                name: button.getAttribute('data-name'),

                description:
                    button.getAttribute('data-desc') || '',

                price:
                    button.getAttribute('data-price'),

                rating:
                    button.getAttribute('data-rating') || '0.0',

                image:
                    button.getAttribute('data-image') || ''

            };

            let exists = favorites.some(function(item) {

                return String(item.id) === String(coffeeId);

            });

            if (!exists) {

                favorites.push(coffee);

            }

        }

        localStorage.setItem(
            'favoriteCoffees',
            JSON.stringify(favorites)
        );

    }


    // استرجاع المفضلة عند فتح Coffee Menu
    window.addEventListener('DOMContentLoaded', () => {

        let favorites =
            JSON.parse(localStorage.getItem('favoriteCoffees')) || [];

        document.querySelectorAll('.heart-btn').forEach(btn => {

            let id = btn.getAttribute('data-id');

            let isFavorite = favorites.some(function(coffee) {

                return String(coffee.id) === String(id);

            });

            if (isFavorite) {

                btn.classList.add('active');

            }

        });

    });


    // إضافة القهوة إلى السلة
    function addToCart(button) {

        let cart =
            JSON.parse(localStorage.getItem('cartItems')) || [];

        let id =
            button.getAttribute('data-id');

        let name =
            button.getAttribute('data-name');

        let price =
            button.getAttribute('data-price') || '0';

        let image =
            button.getAttribute('data-image') || '';

        let existing = cart.find(function(item) {

            return String(item.id) === String(id);

        });

        if (existing) {

            existing.quantity =
                (existing.quantity || 1) + 1;

        } else {

            cart.push({

                id: id,

                name: name,

                price: price,

                image: image,

                quantity: 1

            });

        }

        localStorage.setItem(
            'cartItems',
            JSON.stringify(cart)
        );


        // تغيير شكل الزر لحظياً للتأكيد
        button.classList.add('added');

        button.innerHTML =
            '<i class="fas fa-check me-1"></i> Added';


        setTimeout(function() {

            button.classList.remove('added');

            button.innerHTML =
                '<span style="font-size: 18px;">🛒</span> Add Cart';

        }, 1200);

    }


    // عرض تفاصيل القهوة
    function showDetailsFromButton(button) {

        document.getElementById('modalCoffeeName').innerText =
            button.getAttribute('data-name');

        document.getElementById('modalDesc').innerText =
            button.getAttribute('data-desc') ||
            'No description';

        document.getElementById('modalPrice').innerText =
            button.getAttribute('data-price');

        document.getElementById('modalRating').innerText =
            button.getAttribute('data-rating') ||
            '0.0';

        document.getElementById('modalBeans').innerText =
            button.getAttribute('data-beans') ||
            'N/A';

        document.getElementById('modalServing').innerText =
            button.getAttribute('data-serving');

        document.getElementById('modalCupSize').innerText =
            button.getAttribute('data-cup');

        document.getElementById('modalOrigin').innerText =
            button.getAttribute('data-origin') ||
            'N/A';


        let detailsModal =
            new bootstrap.Modal(
                document.getElementById('coffeeDetailsModal')
            );

        detailsModal.show();

    }


    // فتح نافذة تعديل القهوة
    function openEditModal(button) {

        document.getElementById('edit_id').value =
            button.getAttribute('data-id');

        document.getElementById('edit_name').value =
            button.getAttribute('data-name');

        document.getElementById('edit_category_id').value =
            button.getAttribute('data-cat');

        document.getElementById('edit_description').value =
            button.getAttribute('data-desc');

        document.getElementById('edit_price').value =
            button.getAttribute('data-price');

        document.getElementById('edit_rating').value =
            button.getAttribute('data-rating');

        document.getElementById('edit_beans').value =
            button.getAttribute('data-beans');

        document.getElementById('edit_serving').value =
            button.getAttribute('data-serving');

        document.getElementById('edit_cup_size').value =
            button.getAttribute('data-cup');


        let editModal =
            new bootstrap.Modal(
                document.getElementById('editCoffeeModal')
            );

        editModal.show();

    }


    // إضافة قهوة
    document.getElementById('addCoffeeForm')
        .addEventListener('submit', function(e) {

        e.preventDefault();

        let formData =
            new FormData(this);

        fetch('/api/products', {

            method: 'POST',

            body: formData,

            headers: {
                'Accept': 'application/json'
            }

        })

        .then(res => res.json())

        .then(data => {

            alert('تمت الإضافة بنجاح!');

            location.reload();

        })

        .catch(err => {

            alert('حدث خطأ.');

        });

    });


    // تعديل قهوة
    document.getElementById('editCoffeeForm')
        .addEventListener('submit', function(e) {

        e.preventDefault();

        let id =
            document.getElementById('edit_id').value;

        let formData =
            new FormData(this);

        formData.append('_method', 'PUT');

        fetch('/api/products/' + id, {

            method: 'POST',

            body: formData,

            headers: {
                'Accept': 'application/json'
            }

        })

        .then(res => res.json())

        .then(data => {

            alert('تم التعديل بنجاح!');

            location.reload();

        })

        .catch(err => {

            alert('حدث خطأ.');

        });

    });


    // حذف القهوة
    function deleteCoffee(id) {

        if (confirm('Are you sure?')) {

            fetch('/api/products/' + id, {

                method: 'DELETE',

                headers: {
                    'Accept': 'application/json'
                }

            })

            .then(res => res.json())

            .then(data => {

                alert('تم الحذف بنجاح!');

                location.reload();

            })

            .catch(err => {

                alert('حدث خطأ.');

            });

        }

    }

    // في حال استخدام FormData
formData.append('origin', document.getElementById('origin').value);

// أو في حال استخدام JSON Object
const data = {
    // باقي الحقول ...
    origin: document.getElementById('origin').value
};
    </script>

</x-layouts.app>