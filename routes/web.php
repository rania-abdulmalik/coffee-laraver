<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// مسار الداشبورد الوحيد والصحيح الذي يجلب المنتجات والتصنيفات ويعرض صفحتنا
Route::get('/dashboard', function (Request $request) {
    // نبدأ الاستعلام مع جلب التصنيفات
    $query = Product::with('category');

    // التحقق مما إذا كان المستخدم قد بحث عن شيء
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        });
    }

    // جلب المنتجات (مفلترة أو كاملة)
    $products = $query->get();
    
    // جلب التصنيفات
    $categories = Category::all();
    
    return view('dashboard', compact('products', 'categories'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::view('/favorites', 'favorites')->name('favorites');

Route::view('/cart', 'cart')->name('cart'); 

require __DIR__.'/auth.php';