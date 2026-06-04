<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/track', [LandingController::class, 'track'])->name('track');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/orders/{order}/status', function (\App\Models\Order $order) {
    return response()->json([
        'status' => $order->status,
        'updated_at' => $order->updated_at?->timestamp,
    ]);
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Routes (Must be logged in)
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/orders/{order}', [DashboardController::class, 'orderDetail'])->name('dashboard.order-detail');
    Route::post('/dashboard/orders/{order}/status', [DashboardController::class, 'updateStatus'])->name('dashboard.orders.update-status');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.update-profile');

    // Admin Routes
    Route::get('/admin/reports', [\App\Http\Controllers\AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/orders', [\App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/admin/orders/{order}/status', [\App\Http\Controllers\AdminController::class, 'updateStatus'])->name('admin.orders.update-status');
});
