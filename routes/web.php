<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;

// Customer Controllers
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;

// Customer Panel Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [CustomerProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

    // Profile Module
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications Module
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-all-read', [CustomerNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/{id}/mark-read', [CustomerNotificationController::class, 'markAsRead'])->name('mark-read');
    });
});

// Auth Routes
require __DIR__.'/auth.php';

Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Settings Module
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        // Reports Module
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');

        // Notifications Module
        Route::prefix('notifications')->name('admin.notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::get('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        });

        // Categories Module
        Route::prefix('categories')->name('admin.categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Products Module
        Route::prefix('products')->name('admin.products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
            Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Users Module
        Route::prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Orders Module
        Route::prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
            Route::put('/{id}', [OrderController::class, 'update'])->name('update');
        });
    });
});