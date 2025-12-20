@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Daftar Transaksi</h1>
            <p class="text-gray-600">Ringkasan transaksi pengguna.</p>
        </div>
        @if(Route::has('transaksi.create'))
            <a href="{{ route('transaksi.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Transaksi
            </a>
        @endif
    </div>

    @if($transaksis->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data transaksi.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Pengguna</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Waktu</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaksis as $transaksi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $transaksi->kode }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ optional($transaksi->user)->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs border
                                    @if($transaksi->status === 'selesai' || $transaksi->status === 'paid')
                                        bg-green-50 text-green-700 border-green-200
                                    @elseif($transaksi->status === 'batal' || $transaksi->status === 'failed')
                                        bg-red-50 text-red-700 border-red-200
                                    @else
                                        bg-yellow-50 text-yellow-700 border-yellow-200
                                    @endif">
                                    {{ $transaksi->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ optional($transaksi->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('transaksi.show'))
                                    <a href="{{ route('transaksi.show', $transaksi) }}"
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
            {{ $transaksis->links() }}
        </div>
    @endif
@endsection