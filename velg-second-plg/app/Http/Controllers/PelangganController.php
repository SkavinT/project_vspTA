<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::latest()->paginate(10);
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $loggedEmail = $request->user() ? ($request->user()->email ?? null) : null;

        $rules = [
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:1000',
            'telepon' => 'nullable|string|max:30',
            // enforce email equals the logged-in user's email when available
            'email'   => $loggedEmail
                ? ['required','email','in:'.$loggedEmail,'unique:pelanggans,email']
                : ['nullable','email','unique:pelanggans,email'],
        ];

        $data = $request->validate($rules);

        // always use the logged-in user's email if present
        if ($loggedEmail) {
            $data['email'] = $loggedEmail;
        }

        Pelanggan::create($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dibuat.');
    }

    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:1000',
            'telepon' => 'nullable|string|max:30',
            'email'   => ['nullable','email', Rule::unique('pelanggans','email')->ignore($pelanggan->id)],
        ]);

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
