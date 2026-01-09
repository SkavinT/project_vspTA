<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembelian::query()->with(['supplier','produk']);

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($qry) use ($q) {
                $qry->where('keterangan', 'like', "%{$q}%")
                    ->orWhere('total', 'like', "%{$q}%")
                    ->orWhereDate('tanggal', $q)
                    ->orWhereHas('supplier', fn($s) => $s->where('name','like',"%{$q}%"))
                    ->orWhereHas('produk', fn($p) => $p->where('nama','like',"%{$q}%"));
            });
        }

        $pembelians = $query->orderByDesc('tanggal')->paginate(15)->withQueryString();
        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get(['id','name']);
        $produks   = Produk::orderBy('nama')->get(['id','nama','harga','gambar']);
        return view('pembelian.create', compact('suppliers','produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'     => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id'  => 'required|exists:produks,id',
            'harga_modal' => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'total'       => 'nullable|numeric|min:0',
            'keterangan'  => 'nullable|string|max:1000',
            'gambar'      => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('pembelian', 'public');
        }

        $jumlah = (int)$data['jumlah'];
        $harga  = (float)$data['harga_modal'];
        $total  = $data['total'] ?? ($jumlah * $harga);

        Pembelian::create([
            'tanggal'     => $data['tanggal'],
            'supplier_id' => $data['supplier_id'],
            'product_id'  => $data['product_id'],
            'gambar'      => $path,
            'harga_modal' => $harga,
            'jumlah'      => $jumlah,
            'total'       => $total,
            'keterangan'  => $data['keterangan'] ?? null,
        ]);

        // Tambah stok produk
        $produk = Produk::find($data['product_id']);
        if ($produk) {
            $produk->increment('stok', $jumlah);
        }

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['supplier','produk']);
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        $suppliers = Supplier::orderBy('name')->get(['id','name']);
        $produks   = Produk::orderBy('nama')->get(['id','nama','harga','gambar']);
        return view('pembelian.edit', compact('pembelian','suppliers','produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        $data = $request->validate([
            'tanggal'     => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id'  => 'required|exists:produks,id',
            'harga_modal' => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'total'       => 'nullable|numeric|min:0',
            'keterangan'  => 'nullable|string|max:1000',
            'gambar'      => 'nullable|image|max:2048',
        ]);

        $path = $pembelian->gambar;
        if ($request->hasFile('gambar')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('gambar')->store('pembelian', 'public');
        }

        $jumlah = (int)$data['jumlah'];
        $harga  = (float)$data['harga_modal'];
        $total  = $data['total'] ?? ($jumlah * $harga);

        $pembelian->update([
            'tanggal'     => $data['tanggal'],
            'supplier_id' => $data['supplier_id'],
            'product_id'  => $data['product_id'],
            'gambar'      => $path,
            'harga_modal' => $harga,
            'jumlah'      => $jumlah,
            'total'       => $total,
            'keterangan'  => $data['keterangan'] ?? null,
        ]);

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        if ($pembelian->gambar && Storage::disk('public')->exists($pembelian->gambar)) {
            Storage::disk('public')->delete($pembelian->gambar);
        }
        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
