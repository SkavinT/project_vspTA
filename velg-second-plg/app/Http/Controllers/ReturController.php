<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Retur::with('pelanggan','transaksi');

        // Hanya admin yang boleh melihat semua data
        if ($user && $user->role !== 'admin') {
            if ($user->email) {
                $query->whereHas('pelanggan', function ($q) use ($user) {
                    $q->where('email', $user->email);
                });
            } else {
                // tidak ada email → tidak ada data retur
                $query->whereRaw('1=0');
            }
        }

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
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            $pelanggans = Pelanggan::orderBy('nama')->get(['id','nama']);
        } elseif ($user) {
            $pelanggans = Pelanggan::where('email', $user->email)->get(['id','nama']);
        } else {
            $pelanggans = collect(); // tidak ada data
        }

        $kode = $request->query('kode');
        $prefillTotal = $request->query('total');
        $transaksi = null;

        if ($kode) {
            $userId = Auth::id();
            $transaksi = Transaksi::where('kode', $kode)
                ->when($userId, fn ($q) => $q->where('user_id', $userId))
                ->first();

            if ($transaksi) {
                $prefillTotal = $transaksi->total;
            }
        }

        // ambil daftar transaksi untuk dropdown
        $query = Transaksi::orderByDesc('created_at');

        if (!$user || !in_array($user->role, ['admin','karyawan'])) {
            $query->where('user_id', Auth::id());
        }

        $transaksis = $query->get(['id','kode','total']);

        return view('retur.create', compact('pelanggans', 'transaksi', 'kode', 'prefillTotal', 'transaksis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor'           => 'required|string|max:50|unique:returs,nomor',
            'tanggal'         => 'required|date',
            'customer_id'     => 'nullable|integer|exists:pelanggans,id',
            'transaksi_id'    => 'nullable|integer|exists:transaksis,id',
            'transaksi_kode'  => 'nullable|string|max:50',
            'total'           => 'required|numeric|min:0',
            'status'          => 'required|in:pending,approved,rejected',
            'keterangan'      => 'nullable|string',
            'bukti_files'     => 'nullable|array',
            'bukti_files.*'   => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:51200',
        ]);

        // Jika ada transaksi dipilih/di-klik, pakai datanya
        if (!empty($data['transaksi_id'])) {
            $tx = Transaksi::find($data['transaksi_id']);
            if ($tx) {
                // Pastikan user tidak memakai transaksi orang lain
                $user = Auth::user();
                if ($user && $user->role !== 'admin' && $tx->user_id !== $user->id) {
                    abort(403, 'Tidak boleh memilih transaksi milik orang lain.');
                }

                $data['transaksi_kode'] = $tx->kode;
                $data['total'] = $tx->total;
            }
        }

        // upload bukti seperti sebelumnya
        $paths = [];
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $file) {
                $paths[] = $file->store('retur', 'public');
            }
        }
        $data['bukti_files'] = $paths;

        $user = Auth::user();
        if ($user && $user->role !== 'admin') {
            $data['status'] = 'pending';
        }

        Retur::create($data);

        return redirect()->route('retur.index')->with('success', 'Retur berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Retur $retur)
    {
        $this->authorizeView($retur);

        return view('retur.show', compact('retur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Retur $retur)
    {
        $this->authorizeView($retur);

        return view('retur.edit', compact('retur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Retur $retur)
    {
        $this->authorizeView($retur);

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

        $user = Auth::user();
        if ($user && $user->role !== 'admin') {
            // pelanggan tidak boleh mengubah status
            $data['status'] = $retur->status;
        }

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
        $this->authorizeView($retur);

        $retur->delete();
        return redirect()->route('retur.index')->with('success', 'Retur berhasil dihapus.');
    }

    private function authorizeView(Retur $retur): void
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return;
        }

        $pelanggan = $retur->pelanggan;
        if ($user && $pelanggan && $pelanggan->email === $user->email) {
            return;
        }

        abort(403, 'Tidak diizinkan melihat retur milik orang lain.');
    }
}
