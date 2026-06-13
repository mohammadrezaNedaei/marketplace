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

Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', function () {
        return 'داشبورد فروشنده — به زودی';
    })->name('dashboard');
});

// مسیرهای ادمین
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return 'داشبورد ادمین — به زودی';
    })->name('dashboard');
});

// مسیرهای خریدار
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', function () {
        return 'داشبورد خریدار — به زودی';
    })->name('dashboard');
});

// مسیرهای فروشنده
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Seller\ProductController::class, 'index'])->name('dashboard');
    Route::get('/products/create', [App\Http\Controllers\Seller\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Seller\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Seller\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'destroy'])->name('products.destroy');
});

Route::get('/explore', [App\Http\Controllers\ExploreController::class, 'index'])->name('explore');
Route::get('/api/products', [App\Http\Controllers\ExploreController::class, 'products'])->name('api.products');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');