@extends('layouts.shop')

@section('content')
@php
    $role       = auth()->user()->role ?? null;
    $isSupplier = $role === 'supplier';
@endphp

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold">Pembelian</h1>
        <p class="text-gray-600">Daftar pembelian produk dari supplier.</p>
    </div>
    @if(Route::has('pembelian.create') && auth()->user()?->role === 'admin')
        <a href="{{ route('pembelian.create') }}"
           class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
           + Tambah
        </a>
    @endif
</div>

@if($pembelians->isEmpty())
    <div class="rounded-lg border bg-white p-6 text-center text-gray-600">Belum ada pembelian.</div>
@else
<div class="overflow-x-auto rounded-lg border bg-white">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Supplier</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Produk</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Gambar</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Harga Modal</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Jumlah</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Total</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Keterangan</th>
                @if($isSupplier)
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($pembelians as $pb)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm">{{ $pb->tanggal?->format('d M Y') }}</td>
                <td class="px-4 py-3 text-sm">{{ $pb->supplier?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-sm">{{ $pb->produk?->nama ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($pb->gambar)
                        <img src="{{ asset('storage/'.$pb->gambar) }}" alt="gambar"
                             class="h-16 w-24 object-cover rounded-md border border-gray-200">
                    @else
                        <span class="text-gray-400 text-sm">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-right">Rp {{ number_format($pb->harga_modal,0,',','.') }}</td>
                <td class="px-4 py-3 text-sm text-right">{{ $pb->jumlah }}</td>
                <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                    Rp {{ number_format($pb->total,0,',','.') }}
                </td>

                <td class="px-4 py-3 text-sm">
                    @php $status = $pb->status ?? 'dipesan'; @endphp
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs border
                        @if($status === 'dipesan')
                            bg-yellow-50 text-yellow-700 border-yellow-200
                        @elseif($status === 'dikirim')
                            bg-blue-50 text-blue-700 border-blue-200
                        @elseif($status === 'diterima' || $status === 'selesai')
                            bg-green-50 text-green-700 border-green-200
                        @elseif($status === 'dibatalkan')
                            bg-red-50 text-red-700 border-red-200
                        @else
                            bg-gray-50 text-gray-700 border-gray-200
                        @endif
                    ">
                        {{ ucfirst($status) }}
                    </span>
                </td>

                <td class="px-4 py-3 text-sm">{{ $pb->keterangan ?? '—' }}</td>

                @if($isSupplier)
                    <td class="px-4 py-3 text-sm text-right">
                        <a href="{{ route('pembelian.edit', $pb) }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            Edit
                        </a>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $pembelians->links() }}</div>
@endif
@endsection