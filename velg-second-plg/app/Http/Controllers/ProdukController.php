<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $produks = Produk::query()
            ->when($q !== '', function ($query) use ($q) {
                $like = '%' . $q . '%';
                $query->where(function ($subQuery) use ($like) {
                    $subQuery->where('nama', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('produk.index', compact('produks', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $produks   = Produk::all();   // kalau tidak perlu, bisa dihapus dan sesuaikan view

        return view('produk.create', compact('suppliers', 'produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:200',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $path = $request->hasFile('foto')
            ? $request->file('foto')->store('produk', 'public')
            : null;

        $produk = new Produk();
        $produk->nama = $data['nama'];
        $produk->harga = $data['harga'];
        $produk->stok = $data['stok'] ?? 0;
        $produk->deskripsi = $data['deskripsi'] ?? null;
        $produk->gambar = $path; // simpan ke kolom 'gambar'
        $produk->save();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // delete old image if exists
            if (!empty($produk->foto)) {
                $oldPath = str_starts_with($produk->foto, 'storage/')
                    ? substr($produk->foto, strlen('storage/'))
                    : $produk->foto;

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        if (!empty($produk->gambar)) {
            $oldPath = str_starts_with($produk->gambar, 'storage/')
                ? substr($produk->gambar, strlen('storage/'))
                : $produk->gambar;

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
