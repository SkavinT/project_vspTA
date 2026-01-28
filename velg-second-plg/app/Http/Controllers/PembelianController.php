<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $query = Pembelian::query()->with(['supplier','produk']);

        if ($user->role === 'supplier') {
            $supplier = Supplier::where('email', $user->email)->first();
            if (!$supplier) {
                // supplier belum terhubung ke data supplier manapun → tidak ada data
                $query->whereRaw('1=0');
            } else {
                $query->where('supplier_id', $supplier->id);
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($qry) use ($q) {
                $qry->where('keterangan', 'like', "%{$q}%")
                    ->orWhere('total', 'like', "%{$q}%")
                    ->orWhereDate('tanggal', $q)
                    ->orWhereHas('supplier', fn($s) => $s->where('name','like',"%{$q}%"))
                    ->orWhereHas('produk', fn($p) => $p->where('nama','like',"%{$q}%"));
            });
        }

        $pembelians = $query->orderByDesc('tanggal')->paginate(15)->withQueryString();
        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get(['id','name']);
        $produks   = Produk::orderBy('nama')->get(['id','nama','harga','gambar']);
        return view('pembelian.create', compact('suppliers','produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'tanggal'     => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id'  => 'required|exists:produks,id',
            'harga_modal' => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'total'       => 'nullable|numeric|min:0',
            'keterangan'  => 'nullable|string|max:1000',
            'gambar'      => 'nullable|image|max:2048',
            'status'      => 'nullable|string|in:dipesan,dikirim,diterima,selesai,dibatalkan',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('pembelian', 'public');
        }

        $jumlah = (int) $data['jumlah'];
        $harga  = (float) $data['harga_modal'];
        $total  = $data['total'] ?? ($jumlah * $harga);
        $status = $data['status'] ?? 'dipesan';

        $pembelian = Pembelian::create([
            'tanggal'     => $data['tanggal'],
            'supplier_id' => $data['supplier_id'],
            'product_id'  => $data['product_id'],
            'gambar'      => $path,
            'harga_modal' => $harga,
            'jumlah'      => $jumlah,
            'total'       => $total,
            'keterangan'  => $data['keterangan'] ?? null,
            'status'      => $status,
        ]);

        // Stok hanya bertambah jika langsung dibuat dengan status 'diterima'
        if ($status === 'diterima') {
            $produk = Produk::find($data['product_id']);
            if ($produk) {
                $produk->increment('stok', $jumlah);
            }
        }

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        $this->authorizePembelian($pembelian);

        $suppliers = Supplier::orderBy('name')->get(['id','name']);
        $produks   = Produk::orderBy('nama')->get(['id','nama','harga','gambar']);
        return view('pembelian.edit', compact('pembelian','suppliers','produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        $this->authorizePembelian($pembelian);

        $user      = Auth::user();
        $oldStatus = $pembelian->status ?? 'dipesan';

        // JIKA SUPPLIER → hanya boleh ubah status
        if ($user && $user->role === 'supplier') {
            $data = $request->validate([
                'status' => 'required|string|in:dipesan,dikirim,diterima,selesai,dibatalkan',
            ]);

            $newStatus = $data['status'];

            $pembelian->update([
                'status' => $newStatus,
            ]);

            // stok naik hanya saat status bergeser ke "diterima"
            if ($oldStatus !== 'diterima' && $newStatus === 'diterima') {
                $produk = Produk::find($pembelian->product_id);
                if ($produk) {
                    $produk->increment('stok', $pembelian->jumlah);
                }
            }

            return redirect()->route('pembelian.index')
                ->with('success', 'Status pembelian berhasil diperbarui.');
        }

        // JIKA ADMIN → boleh edit semua field (logika lama)
        $data = $request->validate([
            'tanggal'     => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id'  => 'required|exists:produks,id',
            'harga_modal' => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'total'       => 'nullable|numeric|min:0',
            'keterangan'  => 'nullable|string|max:1000',
            'gambar'      => 'nullable|image|max:2048',
            'status'      => 'nullable|string|in:dipesan,dikirim,diterima,selesai,dibatalkan',
        ]);

        $path = $pembelian->gambar;
        if ($request->hasFile('gambar')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('gambar')->store('pembelian', 'public');
        }

        $jumlah    = (int) $data['jumlah'];
        $harga     = (float) $data['harga_modal'];
        $total     = $data['total'] ?? ($jumlah * $harga);
        $oldStatus = $pembelian->status ?? 'dipesan';

        // Admin boleh mengubah status; jika tidak diisi, pakai status lama
        $newStatus = $data['status'] ?? $oldStatus;

        $pembelian->update([
            'tanggal'     => $data['tanggal'],
            'supplier_id' => $data['supplier_id'],
            'product_id'  => $data['product_id'],
            'gambar'      => $path,
            'harga_modal' => $harga,
            'jumlah'      => $jumlah,
            'total'       => $total,
            'keterangan'  => $data['keterangan'] ?? null,
            'status'      => $newStatus,
        ]);

        // Jika baru saja berubah menjadi 'diterima', tambahkan stok produk
        if ($oldStatus !== 'diterima' && $newStatus === 'diterima') {
            $produk = Produk::find($data['product_id']);
            if ($produk) {
                $produk->increment('stok', $jumlah);
            }
        }

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        if ($pembelian->gambar && Storage::disk('public')->exists($pembelian->gambar)) {
            Storage::disk('public')->delete($pembelian->gambar);
        }
        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
    }

    protected function authorizePembelian(Pembelian $pembelian): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'supplier') {
            $supplier = Supplier::where('email', $user->email)->first();
            if ($supplier && $pembelian->supplier_id === $supplier->id) {
                return;
            }
            abort(403);
        }

        abort(403);
    }
}
