@extends('layouts.shop')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Detail Pembayaran</h1>
            <p class="text-gray-600">Ringkasan lengkap pembayaran pesanan.</p>
        </div>

        <div class="rounded-lg border bg-white p-6 space-y-4">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <p class="text-sm text-gray-500">Order ID</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $pembayaran->order_id }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Status</p>
                    @php($icon = '•')
                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold border
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
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nama Pembayar</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $pembayaran->nama }}
                    </p>
                    @if($pembayaran->user)
                        <p class="text-xs text-gray-500">
                            User: {{ optional($pembayaran->user)->name ?? optional($pembayaran->user)->email }}
                        </p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah</p>
                    <p class="text-lg font-semibold text-indigo-700">
                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                    <p class="text-sm text-gray-900">
                        {{ $pembayaran->metode }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Bukti Pembayaran</p>
                @if($pembayaran->bukti)
                    <a href="{{ asset('storage/'.$pembayaran->bukti) }}" target="_blank"
                       class="inline-flex items-center mt-1 rounded-md border border-indigo-300 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                        Lihat Bukti
                    </a>
                @else
                    <p class="mt-1 text-sm text-gray-400">Belum ada bukti yang diunggah.</p>
                @endif
            </div>

            {{-- Jika nanti items (detail produk) ingin ditampilkan, bisa ditambahkan tabel di sini --}}

            <div class="flex justify-between items-center pt-4 border-t mt-4">
                <a href="{{ route('pembayaran.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@endsection