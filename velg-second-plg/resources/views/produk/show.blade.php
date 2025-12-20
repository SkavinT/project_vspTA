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
                <img src="{{ $img }}" alt="{{ $produk->nama }}" class="w-full object-cover">
            @else
                <div class="h-80 flex items-center justify-center text-gray-400">Tidak ada gambar</div>
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
                <input id="qty" type="number" min="1" value="1"
                       class="w-20 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Tambah ke Keranjang
                </button>
            </div>

            <div class="mt-6">
                <a href="{{ route('produk.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                    ← Kembali ke katalog
                </a>
            </div>
        </div>
    </div>
@endsection