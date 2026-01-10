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

        if ($user && in_array($user->role, ['admin','karyawan'])) {
            // admin & karyawan: lihat semua pelanggan
            $pelanggans = Pelanggan::latest()->paginate(10);
        } elseif ($user) {
            // role lain (guest, dll): hanya data miliknya (berdasarkan email)
            $pelanggans = Pelanggan::where('email', $user->email)
                ->latest()->paginate(10);
        } else {
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
        $user       = Auth::user();
        $loggedEmail = $user?->email;
        $isStaff    = $user && in_array($user->role, ['admin','karyawan']);

        $rules = [
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:1000',
            'telepon' => 'nullable|string|max:30',
        ];

        if ($isStaff) {
            // admin & karyawan boleh isi email pelanggan apa saja
            $rules['email'] = ['required','email','unique:pelanggans,email'];
        } else {
            // pelanggan biasa: email harus sama dengan email akunnya
            $rules['email'] = $loggedEmail
                ? ['required','email','in:'.$loggedEmail,'unique:pelanggans,email']
                : ['nullable','email','unique:pelanggans,email'];
        }

        $data = $request->validate($rules);

        if (!$isStaff && $loggedEmail) {
            // paksa email = email akun untuk non-staff
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

        $user       = Auth::user();
        $canEditAll = $user && in_array($user->role, ['admin','karyawan']);

        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:1000',
            'telepon' => 'nullable|string|max:30',
            'email'   => $canEditAll
                ? ['nullable','email', Rule::unique('pelanggans','email')->ignore($pelanggan->id)]
                : ['nullable','email','in:'.$user->email, Rule::unique('pelanggans','email')->ignore($pelanggan->id)],
        ]);

        if (!$canEditAll) {
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
        if ($user && in_array($user->role, ['admin','karyawan'])) {
            // admin & karyawan boleh semua
            return;
        }
        if ($user && $pelanggan->email === $user->email) {
            // pelanggan biasa hanya datanya sendiri
            return;
        }
        abort(403, 'Tidak diizinkan melihat data pelanggan orang lain.');
    }
}
