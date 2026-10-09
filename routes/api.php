<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public Routes
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/get-categories', [CategoryController::class, 'getCategories']);
Route::get('/categories/{category}', [CategoryController::class, 'getCategoryDetails']);
Route::get('/get-category-details/{category}', [CategoryController::class, 'getCategoryDetails']);

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/get-products', [ProductController::class, 'getProducts']);
Route::get('/products/{product}', [ProductController::class, 'getProductDetails']);
Route::get('/get-product-details/{product}', [ProductController::class, 'getProductDetails']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cart (RESTful and helper aliases)
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::get('/get-cart', [CartController::class, 'getCart']);
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::put('/cart/{id}', [CartController::class, 'updateCartItem']);
    Route::put('/update-cart-item/{id}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/{id}', [CartController::class, 'removeCartItem']);
    Route::delete('/remove-cart-item/{id}', [CartController::class, 'removeCartItem']);

    // Orders (RESTful and helper aliases)
    Route::get('/orders', [OrderController::class, 'myOrders']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::get('/orders/{order}', [OrderController::class, 'showMyOrder']);
    Route::get('/my-orders/{order}', [OrderController::class, 'showMyOrder']);
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::post('/place-order', [OrderController::class, 'placeOrder']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('/cancel-order/{order}', [OrderController::class, 'cancelOrder']);

    // Payment
    Route::post('/payment/process', [PaymentController::class, 'process']);
});
