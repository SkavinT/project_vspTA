@extends('layouts.shop')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Detail Penjualan</h1>
            <p class="text-gray-600">Ringkasan lengkap transaksi penjualan ini.</p>
        </div>

        <div class="rounded-lg border bg-white p-6 space-y-4">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-lg font-semibold text-indigo-700">
                        Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Pelanggan</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $penjualan->customer_name ?? 'Tidak diketahui' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Produk</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ optional($penjualan->produk)->nama ?? 'Tidak diketahui' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Qty</p>
                    <p class="text-sm text-gray-900">
                        {{ number_format($penjualan->quantity, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Harga Satuan</p>
                    <p class="text-sm text-gray-900">
                        Rp {{ number_format($penjualan->price, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p class="text-sm text-gray-900 font-semibold text-indigo-700">
                        Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t mt-4">
                <a href="{{ route('penjualan.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@endsection