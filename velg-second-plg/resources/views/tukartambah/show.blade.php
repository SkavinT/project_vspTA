@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Detail Tukar Tambah</h1>
            <p class="text-gray-600 text-sm">
                Pengajuan oleh {{ $tukarTambah->customer_name }} pada
                {{ optional($tukarTambah->created_at)->format('d M Y H:i') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            @php $st = $tukarTambah->status ?? 'sedang_negosiasi'; @endphp
            <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-medium border
                @if($st === 'disetujui')
                    bg-green-50 text-green-700 border-green-200
                @elseif($st === 'ditolak')
                    bg-red-50 text-red-700 border-red-200
                @else
                    bg-yellow-50 text-yellow-700 border-yellow-200
                @endif">
                @if($st === 'disetujui') Disetujui
                @elseif($st === 'ditolak') Ditolak
                @else Sedang negosiasi
                @endif
            </span>

            <a href="{{ route('tukar-tambah.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                Kembali
            </a>
            @if(auth()->check() && auth()->user()->role === 'admin' && Route::has('tukar-tambah.edit'))
                <a href="{{ route('tukar-tambah.edit', $tukarTambah) }}"
                   class="rounded-md border px-3 py-1.5 text-sm hover:bg-gray-100">
                    Edit
                </a>
            @endif
        </div>
    </div>

    <div class="rounded-lg border bg-white p-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <h2 class="text-sm font-semibold text-gray-500">Nama Pelanggan</h2>
                <p class="mt-1 text-sm text-gray-900">{{ $tukarTambah->customer_name }}</p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-500">No. Telepon</h2>
                <p class="mt-1 text-sm text-gray-900">{{ $tukarTambah->phone ?? '—' }}</p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-500">Barang Lama</h2>
                <p class="mt-1 text-sm text-gray-900">{{ $tukarTambah->item_old }}</p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-500">Barang Baru</h2>
                <p class="mt-1 text-sm text-gray-900">
                    {{ optional($tukarTambah->produk)->nama ?? $tukarTambah->item_new ?? '—' }}
                </p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-500">Perkiraan Harga Tukar Tambah</h2>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $tukarTambah->price !== null ? 'Rp '.number_format($tukarTambah->price, 0, ',', '.') : '—' }}
                </p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-500">Pengaju</h2>
                <p class="mt-1 text-sm text-gray-900">
                    {{ optional($tukarTambah->user)->name ?? '—' }}
                </p>
            </div>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-500 mb-2">Foto Kondisi Barang Lama</h2>
            @if($tukarTambah->condition_image)
                <a href="{{ asset('storage/'.$tukarTambah->condition_image) }}" target="_blank"
                   class="inline-block">
                    <img src="{{ asset('storage/'.$tukarTambah->condition_image) }}"
                         alt="Foto kondisi"
                         class="h-48 w-48 md:h-64 md:w-64 rounded-lg object-cover border">
                </a>
                <p class="mt-1 text-xs text-gray-500">
                    Klik gambar untuk melihat ukuran penuh.
                </p>
            @else
                <p class="text-sm text-gray-500">Belum ada foto kondisi.</p>
            @endif
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-500 mb-1">Catatan Tambahan</h2>
            <p class="text-sm text-gray-900 whitespace-pre-line">
                {{ $tukarTambah->notes ?? '—' }}
            </p>
        </div>
    </div>
</div>
@endsection