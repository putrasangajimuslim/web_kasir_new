<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('dashboard', [ProductController::class, 'index'])->name('dashboard');

// Route::post('cek-kehadiran', [ProductController::class, 'cekKehadiran'])->name('cek-kehadiran');
// Route::post('rekam-kehadiran', [ProductController::class, 'rekamKehadiran'])->name('rekam-kehadiran');

Route::middleware(['isLogin'])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::get('search_products', [ProductController::class, 'searchProducts'])->name('search_products');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::post('update', [ProductController::class, 'update'])->name('update');
        Route::get('destroy{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('users')->as('users.')->middleware(['admin'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('update', [UserController::class, 'update'])->name('update');
        Route::get('aktivasi-akun/{id}', [UserController::class, 'activation_account'])->name('aktivasi-akun');
        Route::get('destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('kategori')->as('kategori.')->middleware(['admin'])->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('update', [CategoryController::class, 'update'])->name('update');
        Route::get('destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('transaksi')->as('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('create', [TransaksiController::class, 'create'])->name('create');
        Route::post('store', [TransaksiController::class, 'store'])->name('store');
        Route::get('edit/{id}', [TransaksiController::class, 'edit'])->name('edit');
        Route::post('cetak-slip', [TransaksiController::class, 'cetakSlip'])->name('cetak-slip');
        Route::post('update', [TransaksiController::class, 'update'])->name('update');
        Route::post('checkout-payment', [TransaksiController::class, 'checkOutPayment'])->name('checkout-payment');
        Route::post('remove-item', [TransaksiController::class, 'removeItem'])->name('remove-item');
        Route::post('reset-all-item', [TransaksiController::class, 'resetAllItem'])->name('reset-all-item');
        Route::get('destroy/{id}', [TransaksiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('laporan')->as('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::post('export-laporan', [LaporanController::class, 'exportExcel'])->name('export-laporan');
    });

    Route::prefix('user-profile')->as('user-profile.')->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('index');
        Route::post('update', [UserProfileController::class, 'updateProfile'])->name('update');
    });
});
