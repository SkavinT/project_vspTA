@props(['produk'])

<a href="{{ route('produk.show', $produk) }}"
   class="group rounded-lg border bg-white shadow-sm overflow-hidden hover:shadow-md transition">
    <div class="aspect-square bg-gray-100 overflow-hidden">
    @php
    $img = $produk->gambar
        ? (\Illuminate\Support\Str::startsWith($produk->gambar, ['http://','https://'])
            ? $produk->gambar
            : asset('storage/'.$produk->gambar))
        : null;
    @endphp
        @if($img)
            <img src="{{ $img }}" alt="{{ $produk->nama }}"
                 class="h-full w-full object-cover group-hover:scale-[1.03] transition">
        @else
            <div class="h-full w-full flex items-center justify-center text-gray-400">
                Tidak ada gambar
            </div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="text-sm font-medium line-clamp-1">{{ $produk->nama }}</h3>
        <div class="mt-2 flex items-center justify-between">
            <span class="text-indigo-600 font-semibold">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </span>
            <span class="text-xs text-gray-500">Stok: {{ $produk->stok }}</span>
        </div>
    </div>
</a>