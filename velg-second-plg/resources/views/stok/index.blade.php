@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Stok</h1>
            <p class="text-gray-600">Daftar stok barang dan ketersediaannya.</p>
        </div>

        <a href="{{ route('stok.create') }}"
           class="inline-flex items-center gap-2 rounded-md border border-indigo-600 bg-white px-4 py-2 text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Stok
        </a>
    </div>

    <form method="get" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/kategori/keterangan..."
                   class="w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
            <button class="rounded-md border px-4 py-2 hover:bg-gray-100">Cari</button>
        </div>
    </form>

    @if($stoks->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center">
            <p class="text-gray-600 mb-4">Belum ada data stok.</p>
            <a href="{{ route('stok.create') }}"
               class="inline-flex items-center gap-2 rounded-md border border-indigo-600 bg-white px-4 py-2 text-indigo-600 hover:bg-indigo-50">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Stok
            </a>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        {{-- Tampilkan kolom Produk jika sudah berelasi --}}
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Kategori</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Jumlah</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($stoks as $stok)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ optional($stok->produk)->nama ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $stok->kategori ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-800">
                                {{ number_format((int)$stok->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                {{ $stok->harga !== null ? 'Rp '.number_format($stok->harga, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 line-clamp-1">{{ $stok->keterangan ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('stok.show'))
                                    <a href="{{ route('stok.show', $stok) }}"
                                       class="rounded-md border px-3 py-1.5 hover:bg-gray-100">
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $stoks->links() }}
        </div>
    @endif
@endsection