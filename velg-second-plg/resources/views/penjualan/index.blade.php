@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Riwayat Penjualan</h1>
            <p class="text-gray-600">Daftar penjualan terbaru dari sistem.</p>
        </div>
        @if(Route::has('penjualan.create'))
            <a href="{{ route('penjualan.create') }}"
               class="inline-flex items-center gap-1 rounded-md border border-indigo-300 bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                <span class="text-lg leading-none">+</span> Tambah Penjualan
            </a>
        @endif
    </div>

    @if($penjualans->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data penjualan.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Produk</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Harga</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Total</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($penjualans as $penjualan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ optional($penjualan->tanggal)->format('d M Y') ?? \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $penjualan->customer_name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ optional($penjualan->produk)->nama ?? ($penjualan->product_name ?? '—') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-800">
                                {{ number_format($penjualan->quantity, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-800">
                                Rp {{ number_format($penjualan->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('penjualan.show'))
                                    <a href="{{ route('penjualan.show', $penjualan) }}"
                                       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
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
            {{ $penjualans->links() }}
        </div>
    @endif
@endsection