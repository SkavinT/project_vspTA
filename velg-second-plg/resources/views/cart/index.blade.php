@extends('layouts.shop')

@section('content')
    @php
        // Prefer controller-passed $cart; fallback to session
        $rows = collect($cart ?? session('cart', []))->values();
        $ids = $rows->pluck('id')->filter()->unique()->values();
        $produkMap = \App\Models\Produk::whereIn('id', $ids)->get()->keyBy('id');
        $total = 0;
    @endphp

    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Keranjang</h1>
        <a href="{{ route('produk.index') }}" class="text-sm text-indigo-600 hover:underline">← Lanjut Belanja</a>
    </div>

    @if($rows->isEmpty())
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Keranjang Anda kosong.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Produk</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Harga</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Qty</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Subtotal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 w-40">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                    @php
                        $p = $produkMap[$row['id']] ?? null;
                        $qty = (int)($row['qty'] ?? 1);
                        $price = (float)($row['price'] ?? 0);
                        $subtotal = $qty * $price;
                        $total += $subtotal;

                        $img = !empty($row['image'])
                            ? (\Illuminate\Support\Str::startsWith($row['image'], ['http://','https://'])
                                ? $row['image']
                                : asset('storage/'.$row['image']))
                            : ($p && $p->gambar
                                ? (\Illuminate\Support\Str::startsWith($p->gambar, ['http://','https://'])
                                    ? $p->gambar
                                    : asset('storage/'.$p->gambar))
                                : null);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="h-16 w-16 bg-gray-100 flex items-center justify-center overflow-hidden rounded">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $row['name'] ?? 'Produk' }}"
                                         style="width:64px;height:64px;object-fit:cover;object-position:center">
                                @else
                                    <span class="text-gray-400 text-xs">No Img</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <div class="font-medium">{{ $row['name'] ?? ($p->nama ?? 'Produk') }}</div>
                            @if(!empty($p?->deskripsi))
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($p->deskripsi, 80) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-800">
                            Rp {{ number_format($price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right">
                            <form action="{{ route('cart.update') }}" method="post" class="inline-flex items-center gap-2 justify-end">
                                @csrf
                                <input type="hidden" name="items[0][id]" value="{{ $row['id'] }}">
                                <input type="number" name="items[0][qty]" min="1" value="{{ $qty }}"
                                       class="w-20 rounded-md border-gray-300 text-right px-2 py-1 focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="submit"
                                        class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-3 py-1.5 text-xs font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Update
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                            <form action="{{ route('cart.remove') }}" method="post" class="inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $row['id'] }}">
                                <button type="submit"
                                        class="inline-flex items-center rounded-md border border-red-300 bg-white text-red-600 px-3 py-1.5 text-xs font-semibold shadow-sm hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-start-3 rounded-lg border bg-white p-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">Total</div>
                    <div class="text-lg font-bold text-indigo-700">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <form action="{{ route('cart.clear') }}" method="post">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-md border border-red-300 bg-white text-red-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Kosongkan
                        </button>
                    </form>
                    <a href="{{ route('produk.index') }}"
                       class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400">
                        Belanja Lagi
                    </a>
                    <button type="button"
                            class="ml-auto inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Checkout (placeholder)
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(!$rows->isEmpty())
        <div class="mb-4 flex items-center justify-between rounded-md border border-indigo-200 bg-indigo-50 p-4 text-indigo-800">
            <div class="text-sm">
                <span class="font-semibold">{{ $totalQty ?? $rows->sum(fn($r) => (int)($r['qty'] ?? 1)) }}</span> barang di keranjang.
                Total sementara: <span class="font-bold">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('produk.index') }}" class="text-sm text-indigo-600 hover:underline">+ Tambah barang</a>
        </div>
    @endif
@endsection