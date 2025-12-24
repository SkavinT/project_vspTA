@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Produk</h1>
        <a href="{{ url()->previous() }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
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

    <form action="{{ route('produk.update', $produk) }}" method="post" enctype="multipart/form-data"
          class="rounded-lg border bg-white p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Nama Produk</label>
            <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}"
                   class="mt-1 w-full rounded border px-3 py-2" required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Harga</label>
                <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" min="0"
                       class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" min="0"
                       class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Foto (opsional)</label>
                <input type="file" name="foto" accept="image/*" class="mt-1 block w-full">
                <p class="text-xs text-gray-500 mt-1">Maks 2 MB. JPG/PNG/WebP.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="mt-1 w-full rounded border px-3 py-2"
                      placeholder="Tulis deskripsi produk...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        @php
            $img = $produk->gambar
                ? (\Illuminate\Support\Str::startsWith($produk->gambar, ['http://','https://'])
                    ? $produk->gambar
                    : asset('storage/'.$produk->gambar))
                : null;
        @endphp
        @if($img)
            <div>
                <span class="block text-sm font-medium mb-2">Pratinjau Foto Saat Ini</span>
                <div class="rounded border bg-gray-50 p-3">
                    <img src="{{ $img }}" alt="{{ $produk->nama }}"
                         style="width:220px;height:220px;object-fit:cover;object-position:center;border-radius:8px;">
                </div>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3">
            <a href="{{ url()->previous() }}"
               class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection