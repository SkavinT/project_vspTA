@extends('layouts.shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Produk Terbaru</h1>
                <p class="text-gray-600 mt-1">Temukan velg second berkualitas dengan harga terbaik.</p>
            </div>
            <a href="{{ route('produk.create') }}"
               class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Produk
            </a>
        </div>

        @if($produks->count() === 0)
            <div class="rounded-lg border bg-white p-8 text-center text-gray-600">Belum ada produk.</div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6"
                 style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;">
                @foreach($produks as $produk)
                    <div class="rounded-lg border bg-white overflow-hidden flex flex-col">
                        <div class="bg-gray-100 flex items-center justify-center" style="height:240px;">
                            @php
                                $img = $produk->gambar
                                    ? (\Illuminate\Support\Str::startsWith($produk->gambar, ['http://','https://'])
                                        ? $produk->gambar
                                        : asset('storage/'.$produk->gambar))
                                    : null;
                            @endphp
                            <a href="{{ route('produk.show', $produk) }}" class="block rounded overflow-hidden">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $produk->nama }}"
                                         style="width:220px;height:220px;object-fit:cover;object-position:center;border-radius:8px;">
                                @else
                                    <div class="text-gray-400 text-sm">Tidak ada gambar</div>
                                @endif
                            </a>
                        </div>

                        <div class="p-3 flex-1 flex flex-col">
                            <a href="{{ route('produk.show', $produk) }}" class="font-medium hover:text-indigo-600">
                                {{ $produk->nama }}
                            </a>
                            <div class="mt-1 text-slate-900 font-semibold">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </div>

                            @if(!empty($produk->deskripsi))
                                <div class="mt-2 text-sm text-gray-600">
                                    {{ \Illuminate\Support\Str::limit($produk->deskripsi, 100) }}
                                </div>
                            @endif
                            <div class="mt-1 text-xs text-gray-500">Stok: {{ (int)($produk->stok ?? 0) }}</div>

                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <form action="{{ route('cart.add') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $produk->id }}">
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                                <form action="{{ route('produk.destroy', $produk) }}" method="post"
                                      onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center rounded-md border border-red-300 bg-white text-red-600 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $produks->links() }}
            </div>
        @endif
    </div>
@endsection