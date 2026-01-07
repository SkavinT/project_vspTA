@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Daftar Transaksi</h1>
            <p class="text-gray-600">Ringkasan transaksi pengguna.</p>
        </div>
        <div class="flex items-center gap-2">
            @if(Route::has('retur.index'))
                <a href="{{ route('retur.index') }}"
                   class="inline-flex items-center rounded-md border border-indigo-300 bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm hover:bg-indigo-50">
                    Cek Pengajuan Retur
                </a>
            @endif
            @if(Route::has('transaksi.create'))
                <a href="{{ route('transaksi.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Tambah Transaksi
                </a>
            @endif
        </div>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">User ID</th>
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
                                {{ $transaksi->user_id ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-700">
                                Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @php($icon = '•')
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold border
                                    @switch($transaksi->status)
                                        @case('diverifikasi') bg-green-50 text-green-700 border-green-200 @php($icon = '✓') @break
                                        @case('terkirim') bg-green-50 text-green-700 border-green-200 @php($icon = '✓') @break
                                        @case('sedang dalam perjalanan') bg-sky-50 text-sky-700 border-sky-200 @php($icon = '🚚') @break
                                        @case('dikemas') bg-indigo-50 text-indigo-700 border-indigo-200 @php($icon = '📦') @break
                                        @case('proses verifikasi') bg-amber-50 text-amber-700 border-amber-200 @php($icon = '⏳') @break
                                        @case('dibatalkan') bg-red-50 text-red-700 border-red-200 @php($icon = '✖') @break
                                        @default bg-gray-50 text-gray-700 border-gray-200
                                    @endswitch">
                                    <span>{{ $icon }}</span>
                                    <span>{{ $transaksi->status }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ optional($transaksi->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if(Route::has('transaksi.show') && $transaksi instanceof \App\Models\Transaksi)
                                        <a href="{{ route('transaksi.show', $transaksi) }}"
                                           class="rounded-md border px-3 py-1.5 hover:bg-gray-100">
                                            Detail
                                        </a>
                                    @endif

                                    @if(Route::has('retur.create'))
                                        <a href="{{ route('retur.create', [
                                                    'kode'  => $transaksi->kode,
                                                    'total' => $transaksi->total,
                                                ]) }}"
                                           class="rounded-md border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50">
                                            Ajukan Retur
                                        </a>
                                    @endif
                                </div>
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