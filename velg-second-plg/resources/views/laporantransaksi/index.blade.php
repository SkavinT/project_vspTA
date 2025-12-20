@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Laporan Transaksi</h1>
            <p class="text-gray-600">Ringkasan transaksi berdasarkan tanggal.</p>
        </div>
        @if(Route::has('laporan-transaksi.create'))
            <a href="{{ route('laporan-transaksi.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Laporan
            </a>
        @endif
    </div>

    @if($laporans->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data laporan transaksi.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-lg border bg-white p-4">
                <div class="text-sm text-gray-600">Item (halaman ini)</div>
                <div class="mt-1 text-2xl font-semibold">{{ $laporans->count() }}</div>
            </div>
            <div class="rounded-lg border bg-white p-4">
                <div class="text-sm text-gray-600">Total (halaman ini)</div>
                <div class="mt-1 text-2xl font-semibold text-indigo-700">
                    Rp {{ number_format($laporans->sum(fn($l) => (float)$l->total), 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laporans as $laporan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($laporan->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $laporan->keterangan ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('laporan-transaksi.show'))
                                    <a href="{{ route('laporan-transaksi.show', $laporan) }}"
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
            {{ $laporans->links() }}
        </div>
    @endif
@endsection