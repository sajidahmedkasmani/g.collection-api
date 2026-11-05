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
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\NewsletterCampaignController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\QueueMonitorController;
use App\Http\Controllers\Admin\PermissionController;

use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerRegisterController;
use App\Http\Controllers\CustomerProfileController;

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

        Route::get('/faqs', 'faqs')->name('faqs');
        
    });

    // Other pages
        Route::view('/shipping', 'frontend.shipping')->name('shipping');
        Route::view('/returns',  'frontend.returns')->name('returns');
        Route::view('/privacy',  'frontend.privacy')->name('privacy');
        Route::view('/terms',    'frontend.terms')->name('terms');

    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
            ->middleware('throttle:5,1')->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
            ->name('newsletter.unsubscribe');

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
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('can:admin.dashboard');

    Route::middleware('can:products.view')->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
    });
    Route::middleware('can:products.create')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/import', [ProductController::class, 'importForm'])->name('products.import.form');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    });
    Route::middleware('can:products.edit')->group(function () {
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('products/{product}', [ProductController::class, 'update']);
    });

    Route::delete('products/bulk-delete', [ProductController::class, 'bulkDelete'])
        ->middleware('can:products.bulk-delete')->name('products.bulk-delete');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])
        ->middleware('can:products.delete')->name('products.destroy');
    Route::post('products/bulk-status', [ProductController::class, 'bulkStatus'])
        ->middleware('can:products.bulk-status')->name('products.bulk-status');

    Route::middleware('can:orders.view')->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });

    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('can:orders.edit')->name('orders.status');

    Route::controller(SettingController::class)->group(function() {
        Route::get('settings', 'edit')->middleware('can:settings.view')->name('settings.edit');
        Route::post('settings', 'update')->middleware('can:settings.edit')->name('settings.update');
    });

    // Website CMS & Content
    Route::controller(CmsController::class)->group(function() {
        Route::get('cms', 'index')->middleware('can:cms.view')->name('cms.index');
        Route::post('cms', 'update')->middleware('can:cms.edit')->name('cms.update');
    });

    // FAQs CMS
    Route::get('faqs', [FaqController::class, 'index'])->middleware('can:faqs.view')->name('faqs.index');
    Route::get('faqs/create', [FaqController::class, 'create'])->middleware('can:faqs.create')->name('faqs.create');
    Route::post('faqs', [FaqController::class, 'store'])->middleware('can:faqs.create')->name('faqs.store');
    Route::get('faqs/{faq}/edit', [FaqController::class, 'edit'])->middleware('can:faqs.edit')->name('faqs.edit');
    Route::put('faqs/{faq}', [FaqController::class, 'update'])->middleware('can:faqs.edit')->name('faqs.update');
    Route::patch('faqs/{faq}', [FaqController::class, 'update'])->middleware('can:faqs.edit');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->middleware('can:faqs.delete')->name('faqs.destroy');
    Route::post('faqs/{faq}/toggle-active', [FaqController::class, 'toggleActive'])
        ->middleware('can:faqs.edit')->name('faqs.toggle-active');
    Route::post('faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');

    // Sliders
    Route::get('sliders', [SliderController::class, 'index'])->middleware('can:sliders.view')->name('sliders.index');
    Route::get('sliders/create', [SliderController::class, 'create'])->middleware('can:sliders.create')->name('sliders.create');
    Route::post('sliders', [SliderController::class, 'store'])->middleware('can:sliders.create')->name('sliders.store');
    Route::get('sliders/{slider}/edit', [SliderController::class, 'edit'])->middleware('can:sliders.edit')->name('sliders.edit');
    Route::put('sliders/{slider}', [SliderController::class, 'update'])->middleware('can:sliders.edit')->name('sliders.update');
    Route::delete('sliders/{slider}', [SliderController::class, 'destroy'])->middleware('can:sliders.delete')->name('sliders.destroy');
    Route::post('sliders/reorder', [SliderController::class, 'reorder'])->middleware('can:sliders.edit')->name('sliders.reorder');

    Route::get('users', [UserController::class, 'index'])->middleware('can:users.view')->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->middleware('can:users.create')->name('users.create');
    Route::post('users', [UserController::class, 'store'])->middleware('can:users.create')->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('can:users.edit')->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('can:users.edit')->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('can:users.delete')->name('users.destroy');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])
        ->name('users.toggle-active')->middleware('can:users.toggle-active');


    Route::controller(PermissionController::class)->group(function() {
        Route::get('permissions', 'index')->middleware('can:users.edit')->name('permissions.index');
        Route::post('permissions', 'store')->middleware('can:users.edit')->name('permissions.store');
        Route::post('permissions/{permission}/toggle-active', 'toggleActive')->middleware('can:users.edit')->name('permissions.toggle-active');
    });


    Route::controller(NewsletterCampaignController::class)->group(function() {
        Route::get('newsletters', 'index')->middleware('can:newsletter.manage')->name('newsletters.index');
        Route::post('newsletters', 'store')->middleware('can:newsletter.manage')->name('newsletters.store');
        Route::post('newsletters/{campaign}/send-now', 'sendNow')->middleware('can:newsletter.manage')->name('newsletters.send-now');
    });

    Route::controller(QueueMonitorController::class)->group(function() {
        Route::get('queue-monitor', 'index')->middleware('can:queue.view')->name('queue.monitor');
        Route::post('queue-monitor/retry', 'retryFailed')>middleware('can:queue.retry')->name('queue.retry');
    });

    Route::controller(CatalogController::class)->group(function() {
        Route::get('/collection', 'index')->name('catalog.collection');
    });


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