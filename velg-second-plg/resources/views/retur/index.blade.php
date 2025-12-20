@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Retur</h1>
            <p class="text-gray-600">Daftar retur pelanggan dan statusnya.</p>
        </div>
        @if(Route::has('retur.create'))
            <a href="{{ route('retur.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Retur
            </a>
        @endif
    </div>

    <form method="get" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor/keterangan..."
                   class="w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
            <button class="rounded-md border px-4 py-2 hover:bg-gray-100">Cari</button>
        </div>
    </form>

    @if($returs->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data retur.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nomor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Pelanggan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($returs as $retur)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $retur->nomor }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($retur->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ optional($retur->pelanggan)->nama ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($retur->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs border
                                    @if($retur->status === 'approved')
                                        bg-green-50 text-green-700 border-green-200
                                    @elseif($retur->status === 'rejected')
                                        bg-red-50 text-red-700 border-red-200
                                    @else
                                        bg-yellow-50 text-yellow-700 border-yellow-200
                                    @endif">
                                    {{ $retur->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 line-clamp-1">
                                {{ $retur->keterangan ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('retur.show'))
                                    <a href="{{ route('retur.show', $retur) }}"
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
            {{ $returs->links() }}
        </div>
    @endif
@endsection