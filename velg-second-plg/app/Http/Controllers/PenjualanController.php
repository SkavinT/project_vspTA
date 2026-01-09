<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Auth::user()?->role;
        if (!in_array($role, ['admin','karyawan'])) {
            abort(403, 'Hanya admin/karyawan dapat melihat data penjualan.');
        }

        $penjualans = \App\Models\Penjualan::latest()->paginate(10);

        // Fallback: show rows derived from Pembayaran items that are paid/verified
        if ($penjualans->count() === 0) {
            $pays = \App\Models\Pembayaran::whereIn('status', ['diverifikasi','terkirim'])
                ->orderByDesc('tanggal')
                ->get();

            $rows = collect();
            foreach ($pays as $p) {
                $items = is_array($p->items) ? $p->items : [];
                foreach ($items as $it) {
                    $productId = (int)($it['id'] ?? 0);
                    $qty       = (int)($it['qty'] ?? 0);
                    $price     = (float)($it['price'] ?? 0);
                    $rows->push((object)[
                        'tanggal'       => \Carbon\Carbon::parse($p->tanggal),
                        'customer_name' => $p->nama,
                        'product_id'    => $productId,
                        'produk'        => \App\Models\Produk::find($productId),
                        'product_name'  => $it['name'] ?? null,
                        'quantity'      => $qty,
                        'price'         => $price,
                        'total'         => $qty * $price,
                    ]);
                }
            }

            $penjualans = new \Illuminate\Pagination\LengthAwarePaginator(
                $rows, $rows->count(), 10, request()->input('page', 1),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penjualan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';

        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'customer_name' => 'nullable|string|max:255', // will be overridden
            'product_id'    => 'required|integer|exists:produks,id',
            'quantity'      => 'required|integer|min:1',
            // only admins can pass a pelanggan_id
            'pelanggan_id'  => $isAdmin ? 'nullable|integer|exists:pelanggans,id' : 'prohibited',
        ]);

        // Resolve customer_name based on role
        if ($isAdmin && $request->filled('pelanggan_id')) {
            $pl = Pelanggan::find($request->integer('pelanggan_id'));
            $customerName = $pl?->nama ?? ($validated['customer_name'] ?? $user?->name ?? '');
        } else {
            $customerName = $user?->name ?? '';
        }

        $produk = Produk::findOrFail((int)$validated['product_id']);
        $price  = (float) $produk->harga;
        $qty    = (int) $validated['quantity'];
        $total  = $qty * $price;

        // Pastikan stok cukup
        if ($produk->stok < $qty) {
            return back()
                ->withErrors(['quantity' => 'Stok produk tidak mencukupi. Stok tersedia: '.$produk->stok])
                ->withInput();
        }

        Penjualan::create([
            'tanggal'       => $validated['tanggal'],
            'customer_name' => $customerName,
            'product_id'    => (int)$validated['product_id'],
            'quantity'      => $qty,
            'price'         => $price,
            'total'         => $total,
        ]);

        // Kurangi stok produk
        $produk->decrement('stok', $qty);

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        return view('penjualan.edit', compact('penjualan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';

        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'customer_name' => 'nullable|string|max:255',
            'product_id'    => 'required|integer|exists:produks,id',
            'quantity'      => 'required|integer|min:1',
            'pelanggan_id'  => $isAdmin ? 'nullable|integer|exists:pelanggans,id' : 'prohibited',
        ]);

        if ($isAdmin && $request->filled('pelanggan_id')) {
            $pl = Pelanggan::find($request->integer('pelanggan_id'));
            $customerName = $pl?->nama ?? ($validated['customer_name'] ?? $penjualan->customer_name);
        } else {
            $customerName = $user?->name ?? $penjualan->customer_name;
        }

        $produk = Produk::findOrFail((int)$validated['product_id']);
        $price  = (float) $produk->harga;
        $qty    = (int) $validated['quantity'];
        $total  = $qty * $price;

        $penjualan->update([
            'tanggal'       => $validated['tanggal'],
            'customer_name' => $customerName,
            'product_id'    => (int)$validated['product_id'],
            'quantity'      => $qty,
            'price'         => $price,
            'total'         => $total,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dihapus.');
    }
}
