<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ubah paginate sesuai kebutuhan
        $transaksis = Transaksi::latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transaksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // contoh fields — ganti sesuai struktur Anda
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:transaksis,kode',
            'user_id' => 'required|integer|exists:users,id',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            // tambahkan field lainnya di sini
        ]);

        $transaksi = Transaksi::create($validated);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        return view('transaksi.edit', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // contoh rules update — unique kecuali record saat ini
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:transaksis,kode,' . $transaksi->id,
            'user_id' => 'required|integer|exists:users,id',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            // tambahkan field lainnya di sini
        ]);

        $transaksi->update($validated);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
