<?php

namespace App\Http\Controllers;

use App\Models\TukarTambah;
use Illuminate\Http\Request;

class TukarTambahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil semua / paginasi
        $items = TukarTambah::latest()->paginate(15);

        // sesuaikan nama view
        return view('tukar-tambah.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tukar-tambah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        $tukar = TukarTambah::create($data);

        return redirect()->route('tukar-tambah.index')
                         ->with('success', 'Data berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TukarTambah $tukarTambah)
    {
        return view('tukar-tambah.show', compact('tukarTambah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TukarTambah $tukarTambah)
    {
        return view('tukar-tambah.edit', compact('tukarTambah'));
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
}