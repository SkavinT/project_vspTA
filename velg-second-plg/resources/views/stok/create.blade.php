@extends('layouts.shop')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold">Tambah Stok</h1>
    <a href="{{ route('stok.index') }}" class="rounded-md border px-4 py-2 hover:bg-gray-100">← Kembali</a>
</div>

<div class="rounded-lg border bg-white p-6">
    <form method="POST" action="{{ route('stok.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium">Produk</label>
            <select name="produk_id" class="mt-1 w-full rounded-md border-gray-300" required>
                <option value="" disabled selected>Pilih produk</option>
                @foreach($produks as $id => $nama)
                    <option value="{{ $id }}" @selected(old('produk_id', request('produk_id')) == $id)>{{ $nama }}</option>
                @endforeach
            </select>
            @error('produk_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Nama Stok</label>
            <input name="nama" value="{{ old('nama') }}" class="mt-1 w-full rounded-md border-gray-300" required>
            @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium">Kategori</label>
                <input name="kategori" value="{{ old('kategori') }}" class="mt-1 w-full rounded-md border-gray-300">
                @error('kategori')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Jumlah</label>
                <input type="number" name="jumlah" value="{{ old('jumlah') }}" class="mt-1 w-full rounded-md border-gray-300" required>
                @error('jumlah')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Harga</label>
                <input type="number" step="0.01" name="harga" value="{{ old('harga') }}" class="mt-1 w-full rounded-md border-gray-300">
                @error('harga')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" rows="3" class="mt-1 w-full rounded-md border-gray-300">{{ old('keterangan') }}</textarea>
            @error('keterangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Simpan</button>
    </form>
</div>
@endsection