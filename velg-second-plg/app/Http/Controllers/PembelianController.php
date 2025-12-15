<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier; // hapus atau ubah jika tidak ada
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembelian::query()->with('supplier'); // pastikan relasi supplier ada di model
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qry) use ($q) {
                $qry->where('id', $q)
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $pembelians = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();
        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::pluck('nama', 'id'); // ubah kolom jika berbeda
        return view('pembelian.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'total' => 'required|numeric',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        Pembelian::create($data);

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        $pembelian->load('supplier'); // eager load relasi bila perlu
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        $suppliers = Supplier::pluck('nama', 'id');
        return view('pembelian.edit', compact('pembelian', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'total' => 'required|numeric',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $pembelian->update($data);

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
