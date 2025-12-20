@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Pembelian</h1>
            <p class="text-gray-600">Riwayat pembelian dari supplier.</p>
        </div>
        @if(Route::has('pembelian.create'))
            <a href="{{ route('pembelian.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Pembelian
            </a>
        @endif
    </div>

    <form method="get" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tanggal, supplier, keterangan..."
                   class="w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
            <button class="rounded-md border px-4 py-2 hover:bg-gray-100">Cari</button>
        </div>
    </form>

    @if($pembelians->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data pembelian.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Supplier</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Total</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pembelians as $pembelian)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ optional($pembelian->supplier)->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $pembelian->keterangan ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('pembelian.show'))
                                    <a href="{{ route('pembelian.show', $pembelian) }}"
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
            {{ $pembelians->links() }}
        </div>
    @endif
@endsection