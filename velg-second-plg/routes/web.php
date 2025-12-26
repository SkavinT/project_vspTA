<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LaporanTransaksiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TukarTambahController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [ProdukController::class, 'index'])->name('home');

// Produk (index, show, create, store, edit, update)
Route::resource('produk', ProdukController::class)->only(['index','show','create','store','edit','update']);
Route::delete('produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

Route::resource('penjualan', PenjualanController::class)->only(['index','show','create','store']);
Route::resource('pembelian', PembelianController::class)->only(['index','show']);
Route::resource('transaksi', TransaksiController::class)->only(['index','show']);
Route::resource('laporan-transaksi', LaporanTransaksiController::class)->only(['index','show']);
Route::resource('pembayaran', PembayaranController::class)
    ->only(['index','show','create','store','edit','update','destroy']);
Route::resource('stok', StokController::class)->only(['index','show']);
Route::resource('retur', ReturController::class)->only(['index','show']);

Route::resource('tukar-tambah', TukarTambahController::class)->only(['index','show']);
Route::resource('pelanggan', PelangganController::class)
    ->only(['index','show','create','store','edit','update','destroy']);
Route::resource('suppliers', SupplierController::class)->only(['index','show']);
Route::resource('penggunas', PenggunaController::class)->only(['index','show']);

// Cart (semua butuh login)
Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('auth')->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->middleware('auth')->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->middleware('auth')->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->middleware('auth')->name('cart.clear');

// Dashboard → beranda produk
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/sukses', [CheckoutController::class, 'success'])->name('checkout.success');

require __DIR__.'/auth.php';