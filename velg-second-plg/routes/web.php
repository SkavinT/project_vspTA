<?php

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

Route::get('/', [ProdukController::class, 'index'])->name('home');
Route::resource('produk', ProdukController::class)->only(['index','show']);
Route::resource('penjualan', PenjualanController::class)->only(['index','show']);
Route::resource('pembelian', PembelianController::class)->only(['index','show']);
Route::resource('transaksi', TransaksiController::class)->only(['index','show']);
Route::resource('laporan-transaksi', LaporanTransaksiController::class)->only(['index','show']);
Route::resource('pembayaran', PembayaranController::class)->only(['index','show']);
Route::resource('stok', StokController::class)->only(['index','show','create','store']);;
Route::resource('retur', ReturController::class)->only(['index','show']);
Route::resource('tukar-tambah', TukarTambahController::class)->only(['index','show']);
Route::resource('pelanggan', PelangganController::class)->only(['index','show']);
Route::resource('suppliers', SupplierController::class)->only(['index','show']);
Route::resource('penggunas', PenggunaController::class)->only(['index','show']);

Route::get('/', [ProdukController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';