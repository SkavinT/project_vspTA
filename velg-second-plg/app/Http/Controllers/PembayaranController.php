<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil semua pembayaran, paginasi 15 per halaman
        $pembayarans = Pembayaran::latest()->paginate(15);
        return view('pembayaran.index', compact('pembayarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pembayaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'nama'     => 'required|string|max:255',
            'jumlah'   => 'required|numeric|min:0',
            'metode'   => 'required|string|max:100',
            'tanggal'  => 'required|date',
            'bukti'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status'   => ['required', Rule::in([
                'proses verifikasi',
                'diverifikasi',
                'dikemas',
                'sedang dalam perjalanan',
                'terkirim',
                'dibatalkan',
            ])],
        ]);
        $data['user_id'] = Auth::id(); // only show-own-data later
        $data['status'] = $data['status'] ?? 'proses verifikasi';

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti_pembayaran', 'public');
        }

        Pembayaran::create($data);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'karyawan'], true)) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('pembayaran.edit', compact('pembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'karyawan'], true)) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $data = $request->validate([
            'status' => ['required', Rule::in([
                'proses verifikasi',
                'diverifikasi',
                'dikemas',
                'sedang dalam perjalanan',
                'terkirim',
                'dibatalkan',
            ])],
        ]);

        $pembayaran->update(['status' => $data['status']]);

        // Only generate sales when paid/verified
        if (in_array($data['status'], ['diverifikasi','terkirim'], true) && is_array($pembayaran->items)) {
            $already = Penjualan::where('payment_id', $pembayaran->id)->exists();

            if (!$already) {
                foreach ($pembayaran->items as $it) {
                    $productId = (int)($it['id'] ?? 0);
                    $qty       = (int)($it['qty'] ?? 1);
                    $price     = (float)(Produk::find($productId)?->harga ?? ($it['price'] ?? 0));
                    $total     = $qty * $price;

                    Penjualan::create([
                        'payment_id'    => $pembayaran->id,
                        'tanggal'       => $pembayaran->tanggal ?? now()->toDateString(),
                        'customer_name' => $pembayaran->nama,
                        'product_id'    => $productId,
                        'quantity'      => $qty,
                        'price'         => $price,
                        'total'         => $total,
                    ]);
                }
            }
        }

        // Sinkron status ke transaksi:
        // - kode transaksi: ORD-YYYYMMDD-RANDOM6
        // - pembayaran.order_id: semua digit dari kode (YYYYMMDD + digit random)
        $numericId = (string) $pembayaran->order_id;

        if (strlen($numericId) >= 8) {
            $datePart = substr($numericId, 0, 8); // contoh: 20260109

            \App\Models\Transaksi::where('user_id', $pembayaran->user_id)
                ->where('kode', 'like', 'ORD-' . $datePart . '-%')
                ->where('total', $pembayaran->jumlah)
                ->update(['status' => $data['status']]);
        }

        return redirect()->route('pembayaran.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
            Storage::disk('public')->delete($pembayaran->bukti);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
