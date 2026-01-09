<?php

namespace App\Http\Controllers;

use App\Models\LaporanTransaksi;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\TukarTambah;
use Illuminate\Http\Request;

class LaporanTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mulai   = $request->input('mulai');
        $selesai = $request->input('selesai');

        $totalPenjualan = Penjualan::when($mulai, fn($q) => $q->whereDate('tanggal', '>=', $mulai))
            ->when($selesai, fn($q) => $q->whereDate('tanggal', '<=', $selesai))
            ->sum('total');

        $totalPembelian = Pembelian::when($mulai, fn($q) => $q->whereDate('tanggal', '>=', $mulai))
            ->when($selesai, fn($q) => $q->whereDate('tanggal', '<=', $selesai))
            ->sum('total');

        // Total tukar tambah yang sudah disetujui
        $totalTukarTambah = TukarTambah::where('status', 'disetujui')
            ->when($mulai, fn($q) => $q->whereDate('created_at', '>=', $mulai))
            ->when($selesai, fn($q) => $q->whereDate('created_at', '<=', $selesai))
            ->sum('price');

        // Keuntungan: penjualan + tukar tambah - pembelian
        $totalKeuntungan = ($totalPenjualan + $totalTukarTambah) - $totalPembelian;

        $penjualanBulanan = Penjualan::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(total) as total')
            ->when($mulai, fn($q) => $q->whereDate('tanggal', '>=', $mulai))
            ->when($selesai, fn($q) => $q->whereDate('tanggal', '<=', $selesai))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $penjualans = Penjualan::with('produk')
            ->when($mulai, fn($q) => $q->whereDate('tanggal', '>=', $mulai))
            ->when($selesai, fn($q) => $q->whereDate('tanggal', '<=', $selesai))
            ->orderByDesc('tanggal')
            ->limit(20)
            ->get();

        return view('laporantransaksi.index', [
            'mulai'            => $mulai,
            'selesai'          => $selesai,
            'totalPenjualan'   => $totalPenjualan,
            'totalPembelian'   => $totalPembelian,
            'totalTukarTambah' => $totalTukarTambah,
            'totalKeuntungan'  => $totalKeuntungan,
            'penjualanBulanan' => $penjualanBulanan,
            'penjualans'       => $penjualans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laporan_transaksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sesuaikan aturan validasi dengan kolom pada model Anda
        $data = $request->validate([
            'tanggal' => 'required|date',
            'total' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        LaporanTransaksi::create($data);

        return redirect()->route('laporan-transaksi.index')
            ->with('success', 'Laporan transaksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanTransaksi $laporanTransaksi)
    {
        return view('laporan_transaksi.show', compact('laporanTransaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanTransaksi $laporanTransaksi)
    {
        return view('laporan_transaksi.edit', compact('laporanTransaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanTransaksi $laporanTransaksi)
    {
        // Sesuaikan aturan validasi dengan kolom pada model Anda
        $data = $request->validate([
            'tanggal' => 'required|date',
            'total' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $laporanTransaksi->update($data);

        return redirect()->route('laporan-transaksi.index')
            ->with('success', 'Laporan transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanTransaksi $laporanTransaksi)
    {
        $laporanTransaksi->delete();

        return redirect()->route('laporan-transaksi.index')
            ->with('success', 'Laporan transaksi berhasil dihapus.');
    }
}
