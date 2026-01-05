@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Pengajuan Tukar Tambah</h1>
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

    <form action="{{ route('tukar-tambah.store') }}" method="post" enctype="multipart/form-data"
          class="rounded-lg border bg-white p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nama Pelanggan</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                       class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="mt-1 w-full rounded border px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Barang Lama (yang ditukar)</label>
            <input type="text" name="item_old" value="{{ old('item_old') }}"
                   class="mt-1 w-full rounded border px-3 py-2" required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Pilih Produk Baru</label>
                <select name="produk_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" @selected(old('produk_id') == $produk->id)>
                            {{ $produk->nama }} - Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Perkiraan Harga Tukar Tambah (opsional)</label>
                <input type="number" step="1" min="0" name="price" value="{{ old('price') }}"
                       class="mt-1 w-full rounded border px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">
                Foto Kondisi Barang Lama (wajib)
            </label>
            <input type="file" name="condition_image" accept="image/*"
                   class="mt-1 block w-full" required>
            <p class="mt-1 text-xs text-gray-500">
                Upload minimal 1 foto yang jelas. Maksimum 5MB.
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium">Catatan Tambahan</label>
            <textarea name="notes" rows="3"
                      class="mt-1 w-full rounded border px-3 py-2">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tukar-tambah.index') }}"
               class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>
@endsection