<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buyer\DashboardController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/{ticket}', [AdminController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AdminController::class, 'replyTicket'])->name('tickets.reply');
    Route::put('/tickets/{ticket}/status', [AdminController::class, 'updateTicketStatus'])->name('tickets.status');

    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::put('/withdrawals/{withdrawal}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::put('/withdrawals/{withdrawal}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/activity-log', [AdminController::class, 'activityLog'])->name('activity-log');

    Route::get('/card-transfers', [AdminController::class, 'cardTransfers'])->name('card-transfers');
    Route::put('/card-transfers/{cardTransfer}/approve', [AdminController::class, 'approveCardTransfer'])->name('card-transfers.approve');
    Route::put('/card-transfers/{cardTransfer}/reject', [AdminController::class, 'rejectCardTransfer'])->name('card-transfers.reject');
});

Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Seller\ProductController::class, 'index'])->name('dashboard');

    Route::get('/products/create', [App\Http\Controllers\Seller\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Seller\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Seller\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/analytics', [App\Http\Controllers\Seller\ProductController::class, 'analytics'])->name('analytics');
    Route::get('/orders', [App\Http\Controllers\Seller\ProductController::class, 'orders'])->name('orders');

    Route::get('/profile', [App\Http\Controllers\Seller\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Seller\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/api/products', [ExploreController::class, 'products'])->name('api.products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::post('/orders/{product}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay')->middleware('auth');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/orders', [DashboardController::class, 'orders'])->name('api.orders');
    Route::get('/api/saves', [DashboardController::class, 'saves'])->name('api.saves');
    Route::get('/payments', [DashboardController::class, 'payments'])->name('payments');
    Route::get('/api/payments', [DashboardController::class, 'paymentsApi'])->name('api.payments');
    Route::get('/purchases', [DashboardController::class, 'purchasesPage'])->name('purchases');
    Route::get('/saves', [DashboardController::class, 'savesPage'])->name('saves');
});

Route::post('/products/{product}/save', [ProductController::class, 'toggleSave'])->name('products.save')->middleware('auth');
Route::post('/products/{product}/like', [ProductController::class, 'toggleLike'])->name('products.like')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
});

Route::middleware('auth')->group(function () {
    Route::get('/follows', [FollowController::class, 'index'])->name('follows.index');
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/sellers/{seller}/follow', [FollowController::class, 'toggle'])->name('sellers.follow');
});

Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/deposit', [WalletController::class, 'depositChoice'])->name('wallet.deposit.choice');
    Route::get('/wallet/deposit/zarinpal', [WalletController::class, 'depositForm'])->name('wallet.deposit.form');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::get('/wallet/deposit/callback', [WalletController::class, 'depositCallback'])->name('wallet.deposit.callback');
    Route::get('/wallet/withdraw', [WalletController::class, 'withdrawForm'])->name('wallet.withdraw.form');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::get('/wallet/card-transfer', [WalletController::class, 'cardTransferForm'])->name('wallet.card-transfer.form');
    Route::post('/wallet/card-transfer', [WalletController::class, 'cardTransferStore'])->name('wallet.card-transfer.store');
});

Route::get('/sellers/{seller}', [SellerProfileController::class, 'show'])->name('sellers.show');
