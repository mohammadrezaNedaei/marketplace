<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// صفحه اصلی
Route::get('/', function () {
    return view('home');
})->name('home');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// مسیرهای ادمین
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

    // کاربران
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('users.delete');

    // تیکت‌ها
    Route::get('/tickets', [App\Http\Controllers\Admin\AdminController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\Admin\AdminController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Admin\AdminController::class, 'replyTicket'])->name('tickets.reply');
    Route::put('/tickets/{ticket}/status', [App\Http\Controllers\Admin\AdminController::class, 'updateTicketStatus'])->name('tickets.status');

    // محصولات
    Route::get('/products', [App\Http\Controllers\Admin\AdminController::class, 'products'])->name('products');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Admin\AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Admin\AdminController::class, 'deleteProduct'])->name('products.delete');
});

// مسیرهای فروشنده
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Seller\ProductController::class, 'index'])->name('dashboard');

    Route::get('/products/create', [App\Http\Controllers\Seller\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Seller\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Seller\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/analytics', [App\Http\Controllers\Seller\ProductController::class, 'analytics'])->name('analytics');
});

Route::get('/explore', [App\Http\Controllers\ExploreController::class, 'index'])->name('explore');
Route::get('/api/products', [App\Http\Controllers\ExploreController::class, 'products'])->name('api.products');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::post('/orders/{product}', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Buyer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/orders', [App\Http\Controllers\Buyer\DashboardController::class, 'orders'])->name('api.orders');
    Route::get('/api/saves', [App\Http\Controllers\Buyer\DashboardController::class, 'saves'])->name('api.saves');
});

Route::post('/products/{product}/save', [App\Http\Controllers\ProductController::class, 'toggleSave'])->name('products.save')->middleware('auth');

// تیکت‌های پشتیبانی — برای همه کاربران لاگین کرده
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\TicketController::class, 'reply'])->name('tickets.reply');
});

// نظرات — فقط برای کاربران لاگین کرده
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/reply', [App\Http\Controllers\ReviewController::class, 'reply'])->name('reviews.reply');
});
