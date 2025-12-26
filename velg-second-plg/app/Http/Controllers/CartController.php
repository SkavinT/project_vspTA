<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\Produk;

class CartController extends Controller
{
    private function cartKey(Request $request): string
    {
        return Auth::check() ? 'cart_user_'.Auth::id() : 'cart_guest';
    }

    private function getCart(Request $request): array
    {
        $session = $request->session();
        $key = $this->cartKey($request);

        // Migrate legacy 'cart' key to current namespaced key
        if ($session->has('cart') && !$session->has($key)) {
            $session->put($key, $session->get('cart', []));
            $session->forget('cart');
        }

        // If user just logged in, merge/migrate guest cart into user cart
        if (Auth::check() && $session->has('cart_guest')) {
            $guest = $session->get('cart_guest', []);
            $existing = $session->get($key, []);

            foreach ($guest as $id => $item) {
                if (isset($existing[$id])) {
                    $existing[$id]['qty'] += (int)($item['qty'] ?? 0);
                } else {
                    $existing[$id] = $item;
                }
            }

            $session->put($key, $existing);
            $session->forget('cart_guest');
        }

        return $session->get($key, []);
    }

    private function putCart(Request $request, array $cart): void
    {
        $request->session()->put($this->cartKey($request), $cart);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $totalQty = collect($cart)->sum('qty');
        $subtotal = collect($cart)->reduce(fn($c, $i) => $c + ((float)$i['price'] * (int)$i['qty']), 0.0);
        $total = $subtotal;

        return view('cart.index', compact('cart', 'totalQty', 'subtotal', 'total'));
    }

    /**
     * Store newly added product to cart.
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'nullable|integer|min:1',
        ]);
        $qty = max(1, (int)($data['qty'] ?? 1));

        $p = Produk::findOrFail($data['product_id']);
        $cart = $this->getCart($request);

        if (isset($cart[$p->id])) {
            $cart[$p->id]['qty'] += $qty;
        } else {
            $cart[$p->id] = [
                'id' => $p->id,
                'name' => $p->nama ?? 'Produk',
                'price' => (float)($p->harga ?? 0),
                // Use unified image field, fallback to legacy names
                'image' => $p->foto ?? $p->gambar ?? $p->image ?? null,
                'qty' => $qty,
            ];
        }

        $this->putCart($request, $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * Update item quantities in cart.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart($request);
        foreach ($data['items'] as $i) {
            if (isset($cart[$i['id']])) {
                $cart[$i['id']]['qty'] = (int)$i['qty'];
            }
        }
        $this->putCart($request, $cart);
        return back()->with('success', 'Keranjang diperbarui');
    }

    /**
     * Remove an item from cart.
     */
    public function remove(Request $request)
    {
        $data = $request->validate(['id' => 'required|integer']);
        $cart = $this->getCart($request);
        Arr::forget($cart, $data['id']);
        $this->putCart($request, $cart);
        return back()->with('success', 'Produk dihapus');
    }

    /**
     * Clear the cart.
     */
    public function clear(Request $request)
    {
        $request->session()->forget($this->cartKey($request));
        return back()->with('success', 'Keranjang dikosongkan');
    }
}