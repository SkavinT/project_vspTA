<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            $pelanggans = Pelanggan::latest()->paginate(10);
        } elseif ($user) {
            $pelanggans = Pelanggan::where('email', $user->email)
                ->latest()->paginate(10);
        } else {
            // No auth → no data
            $pelanggans = Pelanggan::whereRaw('1=0')->paginate(10);
        }

        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $loggedEmail = $user?->email;

        $rules = [
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:1000',
            'telepon' => 'nullable|string|max:30',
            'email'   => $loggedEmail
                ? ['required','email','in:'.$loggedEmail,'unique:pelanggans,email']
                : ['nullable','email','unique:pelanggans,email'],
        ];

        $data = $request->validate($rules);

        if ($loggedEmail) {
            $data['email'] = $loggedEmail;
        }

        Pelanggan::create($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dibuat.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $this->authorizeView($pelanggan);
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        $this->authorizeView($pelanggan);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $this->authorizeView($pelanggan);

        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';

        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:1000',
            'telepon' => 'nullable|string|max:30',
            'email'   => $isAdmin
                ? ['nullable','email', Rule::unique('pelanggans','email')->ignore($pelanggan->id)]
                : ['nullable','email','in:'.$user->email, Rule::unique('pelanggans','email')->ignore($pelanggan->id)],
        ]);

        if (!$isAdmin) {
            // Non-admin cannot change email away from their own
            $data['email'] = $user->email;
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $this->authorizeView($pelanggan);
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    private function authorizeView(Pelanggan $pelanggan): void
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return;
        }
        if ($user && $pelanggan->email === $user->email) {
            return;
        }
        abort(403, 'Tidak diizinkan melihat data pelanggan orang lain.');
    }
}
