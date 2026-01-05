<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Retur::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $returs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('retur.index', compact('returs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get(['id','nama']);
        return view('retur.create', compact('pelanggans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor'        => 'required|string|max:50|unique:returs,nomor',
            'tanggal'      => 'required|date',
            'customer_id'  => 'nullable|integer|exists:pelanggans,id',
            'total'        => 'required|numeric|min:0',
            'status'       => 'required|in:pending,approved,rejected',
            'keterangan'   => 'nullable|string',
            'bukti_files'  => 'nullable|array',
            'bukti_files.*'=> 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:51200',
        ]);

        $paths = [];
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $file) {
                $paths[] = $file->store('retur', 'public');
            }
        }

        $data['bukti_files'] = $paths;

        Retur::create($data);

        return redirect()->route('retur.index')->with('success', 'Retur berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Retur $retur)
    {
        return view('retur.show', compact('retur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Retur $retur)
    {
        return view('retur.edit', compact('retur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Retur $retur)
    {
        $data = $request->validate([
            'nomor'        => 'required|string|max:50|unique:returs,nomor,' . $retur->id,
            'tanggal'      => 'required|date',
            'customer_id'  => 'nullable|integer|exists:pelanggans,id',
            'total'        => 'required|numeric|min:0',
            'status'       => 'required|in:pending,approved,rejected',
            'keterangan'   => 'nullable|string',
            'bukti_files'  => 'nullable|array',
            'bukti_files.*'=> 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:51200',
        ]);

        $paths = $retur->bukti_files ?? [];

        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $file) {
                $paths[] = $file->store('retur', 'public');
            }
        }

        $data['bukti_files'] = $paths;

        $retur->update($data);

        return redirect()->route('retur.index')->with('success', 'Retur berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Retur $retur)
    {
        $retur->delete();
        return redirect()->route('retur.index')->with('success', 'Retur berhasil dihapus.');
    }
}
