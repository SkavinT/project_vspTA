@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Pembayaran</h1>
            <p class="text-gray-600">Riwayat pembayaran pesanan.</p>
        </div>
        @if(Route::has('pembayaran.create'))
            <a href="{{ route('pembayaran.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Pembayaran
            </a>
        @endif
    </div>

    @if($pembayarans->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data pembayaran.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Order ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Metode</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Bukti</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pembayarans as $pembayaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $pembayaran->nama }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $pembayaran->order_id }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $pembayaran->metode }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs
                                    @switch($pembayaran->status)
                                        @case('diverifikasi') bg-green-50 text-green-700 border border-green-200 @break
                                        @case('terkirim') bg-green-50 text-green-700 border border-green-200 @break
                                        @case('sedang dalam perjalanan') bg-blue-50 text-blue-700 border border-blue-200 @break
                                        @case('dikemas') bg-indigo-50 text-indigo-700 border border-indigo-200 @break
                                        @case('proses verifikasi') bg-yellow-50 text-yellow-700 border border-yellow-200 @break
                                        @case('dibatalkan') bg-red-50 text-red-700 border border-red-200 @break
                                        @default bg-gray-50 text-gray-700 border border-gray-200
                                    @endswitch">
                                    {{ $pembayaran->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $buktiUrl = $pembayaran->bukti ? asset('storage/'.$pembayaran->bukti) : null;
                                @endphp
                                @if($buktiUrl)
                                    <a href="{{ $buktiUrl }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('pembayaran.show'))
                                    <a href="{{ route('pembayaran.show', $pembayaran) }}"
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
            {{ $pembayarans->links() }}
        </div>
    @endif
@endsection