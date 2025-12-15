<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil semua pembayaran, paginasi 15 per halaman
        $pembayarans = Pembayaran::latest()->paginate(15);
        return view('pembayaran.index', compact('pembayarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pembayaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'nama'     => 'required|string|max:255',
            'jumlah'   => 'required|numeric|min:0',
            'metode'   => 'required|string|max:100',
            'tanggal'  => 'required|date',
            'bukti'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status'   => 'nullable|in:pending,terverifikasi,gagal',
        ]);

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti_pembayaran', 'public');
        }

        Pembayaran::create($data);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        return view('pembayaran.edit', compact('pembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'nama'     => 'required|string|max:255',
            'jumlah'   => 'required|numeric|min:0',
            'metode'   => 'required|string|max:100',
            'tanggal'  => 'required|date',
            'bukti'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status'   => 'nullable|in:pending,terverifikasi,gagal',
        ]);

        if ($request->hasFile('bukti')) {
            // hapus file lama jika ada
            if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
                Storage::disk('public')->delete($pembayaran->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('bukti_pembayaran', 'public');
        }

        $pembayaran->update($data);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
            Storage::disk('public')->delete($pembayaran->bukti);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
