<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
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
        Route::post('edit-product-json', [ProductController::class, 'editProducts'])->name('edit-product-json');
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

    Route::prefix('transaksi')->as('transaksi.')->middleware(['admin'])->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('create', [TransaksiController::class, 'create'])->name('create');
        Route::post('store', [TransaksiController::class, 'store'])->name('store');
        Route::get('edit/{id}', [TransaksiController::class, 'edit'])->name('edit');
        Route::post('update', [TransaksiController::class, 'update'])->name('update');
        Route::post('action-item', [TransaksiController::class, 'actionItem'])->name('action-item');
        Route::get('destroy/{id}', [TransaksiController::class, 'destroy'])->name('destroy');
    });

    // Route::prefix('laporan')->as('laporan.')->group(function () {
    //     Route::get('/', [LaporanController::class, 'index'])->name('index');
    //     Route::get('detail-slipgaji', [LaporanController::class, 'detailSlipGaji'])->name('detail-slipgaji');
    //     Route::get('detail-rekapgaji', [LaporanController::class, 'detailRekapGaji'])->name('detail-rekapgaji');
    //     Route::get('print/{id}', [LaporanController::class, 'print'])->name('print');
    //     Route::get('detail-periode-rekapgaji/{bln}/{thn}', [LaporanController::class, 'detailPeriodeRekapGajiPeriode'])->name('detail-periode-rekapgaji');
    //     Route::get('print-detail-periode-rekapgaji/{bln}/{thn}', [LaporanController::class, 'printPeriodeRekapGajiPeriode'])->name('print-detail-periode-rekapgaji');
    // });

});
