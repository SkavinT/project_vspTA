<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Produk; // sesuaikan jika nama model berbeda

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $totalQty = collect($cart)->sum('qty');
        $subtotal = collect($cart)->reduce(fn($c, $i) => $c + ($i['price'] * $i['qty']), 0);
        $total = $subtotal;

        return view('cart.index', compact('cart', 'totalQty', 'subtotal', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'nullable|integer|min:1',
        ]);
        $qty = max(1, (int)($data['qty'] ?? 1));

        $p = Produk::findOrFail($data['product_id']);
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$p->id])) {
            $cart[$p->id]['qty'] += $qty;
        } else {
            $cart[$p->id] = [
                'id' => $p->id,
                'name' => $p->nama ?? $p->name ?? 'Produk',
                'price' => (int)($p->harga ?? $p->price ?? 0),
                'image' => $p->foto ?? $p->image ?? null,
                'qty' => $qty,
            ];
        }

        $request->session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);
        foreach ($data['items'] as $i) {
            if (isset($cart[$i['id']])) $cart[$i['id']]['qty'] = (int)$i['qty'];
        }
        $request->session()->put('cart', $cart);
        return back()->with('success', 'Keranjang diperbarui');
    }

    public function remove(Request $request)
    {
        $data = $request->validate(['id' => 'required|integer']);
        $cart = $request->session()->get('cart', []);
        Arr::forget($cart, $data['id']);
        $request->session()->put('cart', $cart);
        return back()->with('success', 'Produk dihapus');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan');
    }
}