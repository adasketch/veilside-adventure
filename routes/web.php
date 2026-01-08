<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahan import
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

// Redirect login jika user tamu
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Sukses
Route::get('/success/{id}', [TransactionController::class, 'success'])->name('success');

// Proses Checkout
Route::post('/checkout', [TransactionController::class, 'store'])->name('transaction.store');

// Riwayat User Biasa
Route::get('/riwayat', [TransactionController::class, 'myHistory'])->middleware('auth')->name('history');

// Dashboard Admin (Check Role Manual)
Route::get('/admin', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return view('admin.dashboard');
    }
    return redirect('/');
})->name('admin.dashboard');

// === GROUP ROUTES KHUSUS ADMIN ===
Route::middleware(['auth'])->group(function () {

    // Manajemen Produk
    Route::get('/admin/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Manajemen Transaksi
    Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('admin.transactions');

    // [BARU] Route Cetak Laporan (Harus ditaruh sebelum route {id} agar tidak bentrok)
    Route::get('/admin/transactions/print', [TransactionController::class, 'printReport'])->name('transactions.print');

    Route::patch('/admin/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::delete('/admin/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
