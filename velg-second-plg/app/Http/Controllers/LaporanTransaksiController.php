<?php

namespace App\Http\Controllers;

use App\Models\LaporanTransaksi;
use Illuminate\Http\Request;

class LaporanTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporans = LaporanTransaksi::orderBy('tanggal', 'desc')->paginate(15);
        return view('laporan_transaksi.index', compact('laporans'));
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
