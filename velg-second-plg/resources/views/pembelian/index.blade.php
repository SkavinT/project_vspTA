@extends('layouts.shop')

@section('content')
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
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Keterangan</th>
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
                <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">Rp {{ number_format($pb->total,0,',','.') }}</td>
                <td class="px-4 py-3 text-sm">{{ $pb->keterangan ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $pembelians->links() }}</div>
@endif
@endsection