@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Stok Produk</h1>
            <p class="text-gray-600">Daftar stok berdasarkan data produk.</p>
        </div>
        {{-- Tombol tambah stok dihilangkan --}}
    </div>

    <form method="get" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk..."
                   class="w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
            <button class="rounded-md border px-4 py-2 hover:bg-gray-100">Cari</button>
        </div>
    </form>

    @if(($produks->count() ?? 0) === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada produk atau tidak ditemukan.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Produk</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Harga</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Stok</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Deskripsi</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($produks as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $p->nama }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-800">{{ (int)$p->stok }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ \Illuminate\Support\Str::limit($p->deskripsi ?? '—', 80) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right whitespace-nowrap align-middle">
                                <a href="{{ route('produk.edit', $p) }}"
                                   class="inline-flex items-center gap-1.5 rounded-md border border-indigo-300 bg-white text-indigo-600 px-3 py-1.5 text-xs font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 0 1 2.828 2.828l-8.486 8.486a2 2 0 0 1-.828.515l-3.086.88a.5.5 0 0 1-.62-.62l.88-3.086a2 2 0 0 1 .515-.828l8.486-8.486zM12 5l3 3"/>
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $produks->links() }}
        </div>
    @endif
@endsection