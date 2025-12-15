<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // contoh: pencarian sederhana dan pagination
        $query = Retur::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nomor', 'like', "%{$q}%")
                  ->orWhere('keterangan', 'like', "%{$q}%");
        }

        $returs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('returs.index', compact('returs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('returs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // sesuaikan rules dengan kolom nyata di table returs
        $data = $request->validate([
            'nomor'       => 'required|string|max:50|unique:returs,nomor',
            'tanggal'     => 'required|date',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'total'       => 'required|numeric|min:0',
            'status'      => 'required|in:pending,approved,rejected',
            'keterangan'  => 'nullable|string',
        ]);

        Retur::create($data);

        return redirect()->route('returs.index')->with('success', 'Retur berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Retur $retur)
    {
        return view('returs.show', compact('retur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Retur $retur)
    {
        return view('returs.edit', compact('retur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Retur $retur)
    {
        $data = $request->validate([
            'nomor'       => 'required|string|max:50|unique:returs,nomor,' . $retur->id,
            'tanggal'     => 'required|date',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'total'       => 'required|numeric|min:0',
            'status'      => 'required|in:pending,approved,rejected',
            'keterangan'  => 'nullable|string',
        ]);

        $retur->update($data);

        return redirect()->route('returs.index')->with('success', 'Retur berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Retur $retur)
    {
        $retur->delete();

        return redirect()->route('returs.index')->with('success', 'Retur berhasil dihapus.');
    }
}
