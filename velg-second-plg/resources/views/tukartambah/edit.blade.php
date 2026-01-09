@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Tukar Tambah</h1>
        <a href="{{ route('tukar-tambah.index') }}" class="text-sm text-indigo-600 hover:underline">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tukar-tambah.update', $tukarTambah) }}" method="post" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nama Pelanggan</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $tukarTambah->customer_name) }}"
                       class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $tukarTambah->phone) }}"
                       class="mt-1 w-full rounded border px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Barang Lama (yang ditukar)</label>
            <input type="text" name="item_old" value="{{ old('item_old', $tukarTambah->item_old) }}"
                   class="mt-1 w-full rounded border px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Barang Baru</label>
            <input type="text" name="item_new" value="{{ old('item_new', $tukarTambah->item_new) }}"
                   class="mt-1 w-full rounded border px-3 py-2" required>
            <p class="mt-1 text-xs text-gray-500">
                Boleh disesuaikan dengan nama produk yang disetujui.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Perkiraan Harga Tukar Tambah</label>
                <input type="number" step="1" min="0" name="price"
                       value="{{ old('price', $tukarTambah->price) }}"
                       class="mt-1 w-full rounded border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Status Pengajuan</label>
                @php $st = old('status', $tukarTambah->status ?? 'sedang_negosiasi'); @endphp
                <select name="status" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="sedang_negosiasi" @selected($st === 'sedang_negosiasi')>Sedang negosiasi</option>
                    <option value="disetujui"        @selected($st === 'disetujui')>Disetujui</option>
                    <option value="ditolak"          @selected($st === 'ditolak')>Ditolak</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Catatan Tambahan</label>
            <textarea name="notes" rows="3"
                      class="mt-1 w-full rounded border px-3 py-2">{{ old('notes', $tukarTambah->notes) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Foto Kondisi Saat Ini</label>
            @if($tukarTambah->condition_image)
                <div class="mt-1">
                    <a href="{{ asset('storage/'.$tukarTambah->condition_image) }}" target="_blank">
                        <img src="{{ asset('storage/'.$tukarTambah->condition_image) }}"
                             class="h-20 w-20 rounded object-cover border" alt="Foto kondisi">
                    </a>
                </div>
            @else
                <p class="mt-1 text-xs text-gray-500">Belum ada foto kondisi.</p>
            @endif
            <p class="mt-1 text-xs text-gray-500">
                (Penggantian foto bisa ditambah nanti jika diperlukan, saat ini hanya baca saja.)
            </p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tukar-tambah.index') }}"
               class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection