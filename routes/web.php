<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;

use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerRegisterController;

// ===== FRONTEND ROUTES =====
    Route::controller(ShopController::class)->group(function() {
        Route::get('/', 'home')->name('home');

        Route::get('/about-us', 'about')->name('aboutus');

        Route::get('/shop', 'shop')->name('shop');
        Route::get('/product/{slug}', 'product_details')->name('product.details');

        Route::get('/blogs', 'blogs')->name('blogs');
        Route::get('/blogs/{slug}', 'blog_content')->name('blog.content');

        Route::get('/contact', 'contact')->name('contact');
        Route::post('/contact', 'send')->middleware('throttle:5,1')->name('contact.send');
    });


    // ===== CART =====
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // ===== CHECKOUT =====
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');


// ===== FRONTEND ROUTES =====


// ===== CMS ADMIN PANEL =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('products/import', [ProductController::class, 'importForm'])->name('products.import.form');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');


    // Sliders
    Route::controller(SliderController::class)->group(function() {
        Route::get('sliders', 'index')->name('sliders.index');
        Route::get('sliders/create', 'create')->name('sliders.create');
        Route::post('sliders', 'store')->name('sliders.store');
        Route::get('sliders/{slider}/edit', 'edit')->name('sliders.edit');
        Route::put('sliders/{slider}', 'update')->name('sliders.update');
        Route::delete('sliders/{slider}', 'destroy')->name('sliders.destroy');
        Route::post('sliders/reorder', 'reorder')->name('sliders.reorder');
    });


    // Bulk actions
    Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::post('/products/bulk-status', [ProductController::class, 'bulkStatus'])->name('products.bulk-status');
  
    
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('can:users.view');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('can:users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('can:users.create');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('can:users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('can:users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('can:users.delete');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])
        ->name('users.toggle-active')->middleware('can:users.toggle-active');


});




// Customer auth routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login']);
        Route::get('/register', [CustomerRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [CustomerRegisterController::class, 'register']);
    });

    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', function () {
            return view('customer.dashboard');
        })->name('dashboard');

        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    });
});


require __DIR__.'/auth.php';