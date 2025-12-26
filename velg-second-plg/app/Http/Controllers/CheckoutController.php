<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Transaksi;

class CheckoutController extends Controller
{
    private function cartKey(Request $request): string
    {
        return Auth::check() ? 'cart_user_'.Auth::id() : 'cart_guest';
    }

    private function getCart(Request $request): array
    {
        $session = $request->session();
        $key = $this->cartKey($request);

        if ($session->has('cart') && !$session->has($key)) {
            $session->put($key, $session->get('cart', []));
            $session->forget('cart');
        }

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

    public function index(Request $request)
    {
        $cart  = collect($this->getCart($request))->values();
        $total = $cart->reduce(fn($c, $i) => $c + ((float)$i['price'] * (int)$i['qty']), 0.0);
        $user  = Auth::user();

        $pelanggans = collect();
        if ($user && $user->email) {
            $pelanggans = Pelanggan::where('email', $user->email)->orderBy('created_at', 'desc')->get();
        }
        $defaultPelanggan = $pelanggans->first();

        return view('checkout.index', compact('cart','total','user','pelanggans','defaultPelanggan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email',
            'telepon'      => 'nullable|string|max:50',
            'pelanggan_id' => 'required|integer|exists:pelanggans,id',
            'catatan'      => 'nullable|string|max:500',
            'metode'       => 'required|in:cod,transfer,qris',
            'status'       => 'nullable|string',
            'bukti'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:4096',
        ]);

        $cart = collect($this->getCart($request))->values();
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Keranjang kosong.');
        }

        $pelanggan = Pelanggan::findOrFail($data['pelanggan_id']);
        $alamat    = $pelanggan->alamat;

        $total   = $cart->reduce(fn($c, $i) => $c + ((float)$i['price'] * (int)$i['qty']), 0.0);
        $orderId = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_pembayaran', 'public');
        }

        Pembayaran::create([
            'order_id' => (int) preg_replace('/\D+/', '', $orderId), // numeric for your table
            'nama'     => $data['nama'],
            'jumlah'   => $total,
            'metode'   => $data['metode'],
            'tanggal'  => now()->toDateString(),
            'bukti'    => $buktiPath,
            'status'   => $data['status'] ?? 'proses verifikasi',
        ]);

        Transaksi::create([
            'kode'    => $orderId,               // use full order code for uniqueness
            'user_id' => $user?->id,
            'total'   => $total,
            'status'  => $data['status'] ?? 'proses verifikasi',
        ]);

        // Keep last_order for success page
        $request->session()->put('last_order', [
            'id'         => $orderId,
            'user_id'    => $user?->id,
            'customer'   => [
                'nama'    => $data['nama'],
                'email'   => $data['email'],
                'telepon' => $data['telepon'] ?? null,
                'alamat'  => $alamat,
            ],
            'items'      => $cart->toArray(),
            'total'      => $total,
            'status'     => $data['status'] ?? 'proses verifikasi',
            'metode'     => $data['metode'],
            'catatan'    => $data['catatan'] ?? null,
            'bukti'      => $buktiPath,
            'created_at' => now()->toDateTimeString(),
        ]);

        $request->session()->forget($this->cartKey($request));

        return redirect()->route('checkout.success')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function success(Request $request)
    {
        $order = $request->session()->get('last_order');
        return view('checkout.success', compact('order'));
    }
}