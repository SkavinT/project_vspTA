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
Route::resource('produk', ProdukController::class)->only(['index','show']);

Route::middleware(['auth','role:admin'])->group(function () {
    // Admin-only: create, store, destroy
    Route::resource('produk', ProdukController::class)->only(['create','store']);
    Route::delete('produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});

Route::middleware(['auth','role:admin,karyawan'])->group(function () {
    // Admin or Karyawan: edit, update
    Route::resource('produk', ProdukController::class)->only(['edit','update']);
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::resource('penjualan', PenjualanController::class)->only(['index','show','create','store']);
    Route::resource('pembelian', PembelianController::class)->only(['index','show']);
    Route::resource('stok', StokController::class)->only(['index','show']);
    Route::resource('retur', ReturController::class)->only(['index','show']);
    Route::resource('tukar-tambah', TukarTambahController::class)->only(['index','show']);
    Route::resource('suppliers', SupplierController::class)->only(['index','show']);
    Route::resource('penggunas', PenggunaController::class)
        ->only(['index','show','edit','update']);
    Route::resource('laporan-transaksi', LaporanTransaksiController::class)->only(['index','show']);
    Route::resource('pembayaran', PembayaranController::class)->only(['index','show','edit','update','destroy']);
    // Add create/edit/update/destroy for modules you want admin to fully manage
});

Route::resource('penjualan', PenjualanController::class)->only(['index','show','create','store']);
Route::resource('pembelian', PembelianController::class)->only(['index','show']);
Route::resource('laporan-transaksi', LaporanTransaksiController::class)->only(['index','show']);
Route::resource('pembayaran', PembayaranController::class)->only(['index','show']);
Route::resource('stok', StokController::class)->only(['index','show']);
Route::resource('retur', ReturController::class)->only(['index','show']);

Route::resource('tukar-tambah', TukarTambahController::class)->only(['index','show']);
Route::resource('pelanggan', PelangganController::class)
    ->only(['index','show','create','store','edit','update','destroy']);
Route::resource('suppliers', SupplierController::class)->only(['index','show']);
Route::resource('penggunas', PenggunaController::class)->only(['index','show','edit','update']);

// Cart (semua butuh login)
Route::middleware(['auth','role:guest,admin,karyawan'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/sukses', [CheckoutController::class, 'success'])->name('checkout.success');

    // Transaksi (own only; controller already filters by Auth::id())
    Route::resource('transaksi', TransaksiController::class)->only(['index','show']);

    // Pelanggan (kelola alamat)
    Route::resource('pelanggan', PelangganController::class)
        ->only(['index','create','store','edit','update','destroy']);
});

// Supplier: lihat supplier (extend as needed)
Route::middleware(['auth','role:supplier'])->group(function () {
    Route::resource('suppliers', SupplierController::class)->only(['index','show']);
});

// Karyawan: operasional
Route::middleware(['auth','role:karyawan'])->group(function () {
    Route::resource('stok', StokController::class)->only(['index','show']);
    Route::resource('penjualan', PenjualanController::class)->only(['index','show','create','store']);
    Route::resource('pembayaran', PembayaranController::class)->only(['index','show']);
});

// Dashboard → beranda produk
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// If you prefer success page public, move only success out of the group.

require __DIR__.'/auth.php';