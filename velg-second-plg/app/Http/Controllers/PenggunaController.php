<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure the logged-in user exists in penggunas
        if (Auth::check()) {
            $u = Auth::user();
            $existing = Pengguna::where('email', $u->email)->first();
            if (!$existing) {
                Pengguna::create([
                    'nama'     => $u->name,
                    'email'    => $u->email,
                    'password' => $u->getAuthPassword(),
                    'role'     => 'guest',
                ]);
            }
        }

        $penggunas = \App\Models\Pengguna::leftJoin('users', 'users.email', '=', 'penggunas.email')
            ->select('penggunas.*', 'users.role as user_role')
            ->orderBy('penggunas.created_at', 'desc')
            ->paginate(10);

        return view('pengguna.index', compact('penggunas')); // singular
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sesuaikan rules dengan field model Anda
        $data = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:penggunas,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'nullable|in:guest,user,staff,admin', // allow null
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'guest'; // default

        Pengguna::create($data);

          return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengguna $pengguna)
    {
        return view('pengguna.show', compact('pengguna')); // singular
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengguna $pengguna)
    {
        $user = \App\Models\User::where('email', $pengguna->email)->first();
        $currentRole = $user?->role ?? ($pengguna->role ?? 'guest');
        return view('pengguna.edit', compact('pengguna', 'currentRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => ['required','email', Rule::unique('penggunas','email')->ignore($pengguna->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|in:guest,user,staff,karyawan,admin',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $pengguna->update([
            'nama'  => $data['nama'],
            'email' => $data['email'],
            // keep local copy for fallback display
            'role'  => $data['role'],
        ]);

        // Sync role on users table by current (new) email
        \App\Models\User::where('email', $pengguna->email)
            ->update(['role' => $data['role']]);

        return redirect()->route('penggunas.index')->with('success', 'Role pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
