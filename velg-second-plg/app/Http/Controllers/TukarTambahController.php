<?php

namespace App\Http\Controllers;

use App\Models\TukarTambah;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TukarTambahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = TukarTambah::with('produk');

        if (!$user) {
            // tidak login → tidak ada data
            $query->whereRaw('1=0');
        } elseif ($user->role === 'admin') {
            // admin lihat semua
            // tidak ada filter
        } else {
            // pelanggan / karyawan hanya lihat data miliknya
            $query->where('user_id', $user->id);
        }

        $items = $query->latest()->paginate(15);

        // sesuaikan nama view dengan folder "tukartambah"
        return view('tukartambah.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // admin & pelanggan boleh create
        if (!in_array($user->role, ['admin','guest','karyawan'])) {
            abort(403);
        }

        $produks = Produk::orderBy('nama')->get(['id','nama','harga']);

        return view('tukartambah.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'customer_name'   => 'required|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'item_old'        => 'required|string|max:255',
            'produk_id'       => 'required|integer|exists:produks,id',
            'price'           => 'nullable|numeric',
            'notes'           => 'nullable|string',
            'condition_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // simpan foto kondisi
        $path = $request->file('condition_image')->store('tukar-tambah', 'public');
        $data['condition_image'] = $path;

        // simpan product name juga ke item_new (supaya index lama tetap jalan)
        $produk = Produk::find($data['produk_id']);
        $data['item_new'] = $produk?->nama;

        // hubungkan ke user pemilik
        $data['user_id'] = $user->id;

        TukarTambah::create($data);

        return redirect()->route('tukar-tambah.index')
                         ->with('success', 'Pengajuan tukar tambah berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TukarTambah $tukarTambah)
    {
        $this->authorizeView($tukarTambah);

        return view('tukartambah.show', compact('tukarTambah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TukarTambah $tukarTambah)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $produks = Produk::orderBy('nama')->get(['id','nama','harga']);

        return view('tukartambah.edit', compact('tukarTambah','produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TukarTambah $tukarTambah)
    {
        // Ganti rules sesuai kolom model Anda
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'item_old'      => 'required|string|max:255',
            'item_new'      => 'required|string|max:255',
            'price'         => 'nullable|numeric',
            'notes'         => 'nullable|string',
        ]);

        $tukarTambah->update($data);

        return redirect()->route('tukar-tambah.index')
                         ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TukarTambah $tukarTambah)
    {
        $tukarTambah->delete();

        return redirect()->route('tukar-tambah.index')
                         ->with('success', 'Data berhasil dihapus.');
    }

    private function authorizeView(TukarTambah $tukarTambah): void
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return; // admin boleh lihat semua
        }

        if ($user && $tukarTambah->user_id === $user->id) {
            return; // pemilik boleh lihat
        }

        abort(403, 'Tidak diizinkan melihat data tukar tambah orang lain.');
    }
}