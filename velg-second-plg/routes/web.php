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

// Produk (index, show) untuk semua pengunjung
Route::resource('produk', ProdukController::class)
    ->only(['index', 'show'])
    ->whereNumber('produk');

// ======================= ADMIN =========================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Produk: kelola penuh (kecuali index/show sudah di atas)
    Route::resource('produk', ProdukController::class)
        ->only(['create', 'store', 'edit', 'update']);
    Route::delete('produk/{produk}', [ProdukController::class, 'destroy'])
        ->name('produk.destroy');

    // Supplier: HANYA admin yang bisa melihat & kelola
    Route::resource('suppliers', SupplierController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

    // Pembelian: admin boleh buat & hapus
    Route::resource('pembelian', PembelianController::class)
        ->only(['create', 'store', 'destroy']);

    // Retur: penuh untuk admin
    Route::resource('retur', ReturController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update']);

    // Pengguna (manajemen user)
    Route::resource('penggunas', PenggunaController::class)
        ->only(['index', 'show', 'edit', 'update']);

    // Laporan transaksi
    Route::resource('laporan-transaksi', LaporanTransaksiController::class)
        ->only(['index', 'show']);

    // Pembayaran: admin boleh edit/update/destroy data pembayaran
    Route::resource('pembayaran', PembayaranController::class)
        ->only(['edit', 'update', 'destroy']);
});

// ========== ADMIN + KARYAWAN (OPERASIONAL TOKO) ==========
Route::middleware(['auth', 'role:admin,karyawan'])->group(function () {
    // Penjualan
    Route::resource('penjualan', PenjualanController::class)
        ->only(['index', 'show', 'create', 'store']);

    // Pembayaran: lihat daftar & detail
    Route::resource('pembayaran', PembayaranController::class)
        ->only(['index', 'show']);
});

// Stok: bisa dilihat admin, karyawan, dan supplier
Route::middleware(['auth', 'role:admin,karyawan,supplier'])->group(function () {
    Route::resource('stok', StokController::class)
        ->only(['index', 'show']);
});

// ========== ADMIN + SUPPLIER (PEMBELIAN) ==========
// Pembelian: admin & supplier boleh lihat + edit miliknya (filter di controller)
Route::middleware(['auth', 'role:admin,supplier'])->group(function () {
    Route::resource('pembelian', PembelianController::class)
        ->only(['index', 'show', 'edit', 'update']);
});

// ========== CART, CHECKOUT, TRANSAKSI PELANGGAN ==========
Route::middleware(['auth', 'role:guest,admin,karyawan'])->group(function () {
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

    // Transaksi (hanya transaksi milik user tersebut, diatur di controller)
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'show']);

    // Pelanggan (alamat user)
    Route::resource('pelanggan', PelangganController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Tukar Tambah (pengajuan pelanggan/karyawan/admin)
    Route::resource('tukar-tambah', TukarTambahController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update']);
});

// ================== DASHBOARD & PROFIL ==================
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========== UB AH STATUS PEMBAYARAN (CEK ROLE DI CONTROLLER) ==========
Route::middleware(['auth'])->group(function () {
    Route::get('pembayaran/{pembayaran}/status', [PembayaranController::class, 'edit'])
        ->name('pembayaran.karyawan.edit');
    Route::put('pembayaran/{pembayaran}/status', [PembayaranController::class, 'update'])
        ->name('pembayaran.karyawan.update');
});

require __DIR__ . '/auth.php';