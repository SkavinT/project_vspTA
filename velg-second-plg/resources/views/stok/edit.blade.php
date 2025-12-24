@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Produk</h1>
        <a href="{{ route('stok.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
    </div>

    <form action="{{ route('produk.update', $produk) }}" method="post" enctype="multipart/form-data" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $produk->nama) }}" class="mt-1 w-full rounded border px-3 py-2" required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Harga</label>
                <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" min="0" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" min="0" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Foto (opsional)</label>
                <input type="file" name="foto" accept="image/*" class="mt-1 block w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="mt-1 w-full rounded border px-3 py-2">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('stok.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection