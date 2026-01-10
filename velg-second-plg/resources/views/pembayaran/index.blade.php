@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Pembayaran</h1>
            <p class="text-gray-600">Riwayat pembayaran pesanan.</p>
        </div>
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
                                @php($icon = '•')
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold border
                                    @switch($pembayaran->status)
                                        @case('diverifikasi') bg-green-50 text-green-700 border-green-200 @php($icon = '✓') @break
                                        @case('terkirim') bg-green-50 text-green-700 border-green-200 @php($icon = '✓') @break
                                        @case('sedang dalam perjalanan') bg-sky-50 text-sky-700 border-sky-200 @php($icon = '🚚') @break
                                        @case('dikemas') bg-indigo-50 text-indigo-700 border-indigo-200 @php($icon = '📦') @break
                                        @case('proses verifikasi') bg-amber-50 text-amber-700 border-amber-200 @php($icon = '⏳') @break
                                        @case('dibatalkan') bg-red-50 text-red-700 border-red-200 @php($icon = '✖') @break
                                        @default bg-gray-50 text-gray-700 border-gray-200
                                    @endswitch">
                                    <span>{{ $icon }}</span>
                                    <span>{{ $pembayaran->status }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($pembayaran->bukti)
                                    <a href="{{ asset('storage/'.$pembayaran->bukti) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a>
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

                                @if(auth()->user()?->role === 'admin')
                                    <a href="{{ route('pembayaran.edit', $pembayaran) }}"
                                       class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-3 py-1.5 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                                        Edit
                                    </a>
                                    <form action="{{ route('pembayaran.destroy', $pembayaran) }}" method="post" class="inline-block ml-2"
                                          onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md border border-red-300 bg-white text-red-600 px-3 py-1.5 text-sm font-semibold shadow-sm hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">
                                            Hapus
                                        </button>
                                    </form>
                                @endif


                                @if(auth()->user()?->role === 'admin')
                                    <a href="{{ route('pembayaran.edit', $pembayaran) }}"
                                       class="btn btn-sm">
                                        Ubah Status
                                    </a>
                                @elseif(auth()->user()?->role === 'karyawan')
                                    <a href="{{ route('pembayaran.karyawan.edit', $pembayaran) }}"
                                       class="btn btn-sm">
                                        Ubah Status
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