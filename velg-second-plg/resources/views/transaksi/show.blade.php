@extends('layouts.shop')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Detail Transaksi</h1>
            <p class="text-gray-600">Ringkasan lengkap transaksi yang dipilih.</p>
        </div>

        <div class="rounded-lg border bg-white p-6 space-y-4">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <p class="text-sm text-gray-500">Kode Transaksi</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $transaksi->kode }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Status</p>
                    @php($icon = '•')
                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold border
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
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Pengguna</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ optional($transaksi->user)->name ?? optional($transaksi->user)->email ?? 'Tidak diketahui' }}
                    </p>
                    @if($transaksi->user)
                        <p class="text-xs text-gray-500">
                            User ID: {{ $transaksi->user_id }}
                        </p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-lg font-semibold text-indigo-700">
                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Dibuat</p>
                    <p class="text-sm text-gray-800">
                        {{ optional($transaksi->created_at)->format('d M Y H:i') ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Terakhir diubah</p>
                    <p class="text-sm text-gray-800">
                        {{ optional($transaksi->updated_at)->format('d M Y H:i') ?? '—' }}
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-sm text-gray-500">Alamat Pembeli</p>
                <p class="text-sm text-gray-900">
                    {{ $transaksi->alamat ?? 'Tidak tersedia' }}
                </p>
            </div>

            {{-- Jika nanti ada relasi item / detail produk, bisa ditambahkan tabel di sini --}}

            <div class="flex justify-between items-center pt-4 border-t mt-4">
                <a href="{{ route('transaksi.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Kembali ke Daftar
                </a>

                @if(Route::has('retur.create'))
                    <a href="{{ route('retur.create', [
                                'kode'  => $transaksi->kode,
                                'total' => $transaksi->total,
                            ]) }}"
                       class="inline-flex items-center rounded-md border border-indigo-300 bg-white px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                        Ajukan Retur
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection