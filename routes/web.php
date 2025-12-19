<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/sewa', [ProductController::class, 'publicList'])->name('sewa');

Route::get('/form', function () {
    return view('form');
})->name('form');

Route::get('/success/{id}', [TransactionController::class, 'success'])->name('success');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post'); // Aksi tombol login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', function () {
    // Cek manual sederhana apakah user admin
    if (Auth::check() && Auth::user()->role === 'admin') {
        return view('admin.dashboard');
    }
    return redirect('/'); // Tendang jika bukan admin
})->name('admin.dashboard');

Route::middleware(['auth'])->group(function () {
    // Pastikan hanya admin yang bisa akses di dalam controller atau middleware
    Route::get('/admin/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
});


Route::middleware(['not.admin'])->group(function () {

    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/sewa', [ProductController::class, 'publicList'])->name('sewa');

    Route::get('/form', function () {
        return view('form');
    })->name('form');

});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 1. Proses Form Sewa (POST)
Route::post('/checkout', [TransactionController::class, 'store'])->name('transaction.store');

// 2. Halaman Riwayat User (Login only)
Route::get('/riwayat', [TransactionController::class, 'myHistory'])->middleware('auth')->name('history');

// 3. Halaman Riwayat Admin (Admin only)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('admin.transactions');
    Route::patch('/admin/transactions/{id}/status', [TransactionController::class, 'updateStatus'])
         ->name('transactions.updateStatus');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/admin/transactions/{id}', [TransactionController::class, 'destroy'])
         ->name('transactions.destroy');
});
