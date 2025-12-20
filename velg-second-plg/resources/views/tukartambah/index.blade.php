@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Tukar Tambah</h1>
            <p class="text-gray-600">Daftar transaksi tukar tambah.</p>
        </div>
        @if(Route::has('tukar-tambah.create'))
            <a href="{{ route('tukar-tambah.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Tukar Tambah
            </a>
        @endif
    </div>

    @if($items->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data tukar tambah.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Barang Lama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Barang Baru</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Catatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Dibuat</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($items as $tukar)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $tukar->customer_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $tukar->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $tukar->item_old }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $tukar->item_new }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                {{ $tukar->price !== null ? 'Rp '.number_format($tukar->price, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 line-clamp-1">{{ $tukar->notes ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ optional($tukar->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('tukar-tambah.show'))
                                    <a href="{{ route('tukar-tambah.show', $tukar) }}"
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
            {{ $items->links() }}
        </div>
    @endif
@endsection