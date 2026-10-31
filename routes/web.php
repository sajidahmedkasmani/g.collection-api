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

// ===== PUBLIC =====
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');

Route::get('/about-us', [ShopController::class, 'about'])->name('aboutus');
Route::get('/blogs', [ShopController::class, 'blogs'])->name('blogs');
Route::get('/contact', [ShopController::class, 'contact'])->name('contact');

// use App\Http\Controllers\ContactController;

// Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ShopController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');

// ===== CART =====
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// ===== CHECKOUT =====
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');

// ===== ADMIN =====
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
    Route::get('sliders', [\App\Http\Controllers\Admin\SliderController::class, 'index'])->name('sliders.index');
    Route::get('sliders/create', [\App\Http\Controllers\Admin\SliderController::class, 'create'])->name('sliders.create');
    Route::post('sliders', [\App\Http\Controllers\Admin\SliderController::class, 'store'])->name('sliders.store');
    Route::get('sliders/{slider}/edit', [\App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('sliders.edit');
    Route::put('sliders/{slider}', [\App\Http\Controllers\Admin\SliderController::class, 'update'])->name('sliders.update');
    Route::delete('sliders/{slider}', [\App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('sliders.destroy');
    Route::post('sliders/reorder', [\App\Http\Controllers\Admin\SliderController::class, 'reorder'])->name('sliders.reorder');


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

require __DIR__.'/auth.php';