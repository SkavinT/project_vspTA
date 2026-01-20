<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin & karyawan: boleh lihat semua transaksi
        if (in_array($user->role, ['admin', 'karyawan'], true)) {
            $transaksis = Transaksi::with('user')
                ->latest()
                ->paginate(10);

            return view('transaksi.index', compact('transaksis'));
        }

        // Role lain (pelanggan/guest login): hanya transaksi miliknya
        $userId = $user->id;

        $transaksis = Transaksi::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        // Fallback: jika belum ada entry di transaksis, turunkan dari pembayaran user tsb
        if ($transaksis->count() === 0) {
            $pays = Pembayaran::where('user_id', $userId)
                ->orderByDesc('tanggal')
                ->paginate(10);

            $collection = $pays->getCollection()->map(function ($p) use ($userId) {
                $t = new \stdClass();
                $t->kode       = 'PAY-' . $p->order_id;
                $t->user_id    = $userId;
                $t->total      = $p->jumlah;
                $t->status     = $p->status ?? 'proses verifikasi';
                $t->created_at = \Carbon\Carbon::parse($p->tanggal);
                return $t;
            });

            $pays->setCollection($collection);
            $transaksis = $pays;
        }

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transaksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'    => 'required|string|max:50|unique:transaksis,kode',
            'user_id' => 'required|integer|exists:users,id',
            'total'   => 'required|numeric|min:0',
            'status'  => 'required|string|max:50',
            'alamat'  => 'nullable|string|max:255',
        ]);

        Transaksi::create($validated);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Jika bukan admin/karyawan, hanya boleh lihat transaksi miliknya
        if (!in_array($user->role, ['admin', 'karyawan'], true)
            && $transaksi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        return view('transaksi.edit', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'kode'    => 'required|string|max:50|unique:transaksis,kode,' . $transaksi->id,
            'user_id' => 'required|integer|exists:users,id',
            'total'   => 'required|numeric|min:0',
            'status'  => 'required|string|max:50',
            'alamat'  => 'nullable|string|max:255',
        ]);

        $transaksi->update($validated);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
