@extends('layouts.shop')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="rounded-lg border bg-white overflow-hidden">
            @php
                $img = $produk->gambar
                    ? (\Illuminate\Support\Str::startsWith($produk->gambar, ['http://','https://'])
                        ? $produk->gambar
                        : asset('storage/'.$produk->gambar))
                    : null;
            @endphp
            @if($img)
                <img src="{{ $img }}" alt="{{ $produk->nama }}"
                     style="width:600px;height:420px;object-fit:cover;object-position:center"
                     class="mx-auto">
            @else
                <div style="height:320px" class="flex items-center justify-center text-gray-400">Tidak ada gambar</div>
            @endif
        </div>

        <div>
            <h1 class="text-2xl font-semibold">{{ $produk->nama }}</h1>
            <div class="mt-2 text-indigo-600 text-xl font-bold">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </div>
            <div class="mt-1 text-sm text-gray-500">Stok tersedia: {{ $produk->stok }}</div>

            @if($produk->deskripsi)
                <div class="mt-6 prose max-w-none">
                    {!! nl2br(e($produk->deskripsi)) !!}
                </div>
            @endif

            <div class="mt-8 flex items-center gap-3">
                <label for="qty" class="text-sm text-gray-700">Jumlah</label>

                @auth
                    <form action="{{ route('cart.add') }}" method="post" class="flex items-center gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $produk->id }}">
                        <input id="qty" name="qty" type="number" min="1" value="1"
                               class="w-20 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit"
                                class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <input id="qty" type="number" min="1" value="1"
                           class="w-20 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <a href="{{ route('login', ['msg' => 'login-required', 'redirect' => request()->fullUrl()]) }}"
                       class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Tambah ke Keranjang
                    </a>
                @endauth
            </div>

            <div class="mt-6">
                <a href="{{ route('produk.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                    ← Kembali ke katalog
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('produk.destroy', $produk) }}" method="post" class="mt-4"
          onsubmit="return confirm('Yakin hapus produk ini?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center rounded-md border border-red-300 bg-white text-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">
            Hapus Produk
        </button>
    </form>
@endsection