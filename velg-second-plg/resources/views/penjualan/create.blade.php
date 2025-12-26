@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Penjualan</h1>
        <a href="{{ route('penjualan.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    @php
        $produks = \App\Models\Produk::orderBy('nama')->pluck('nama','id');
    @endphp

    <form action="{{ route('penjualan.store') }}" method="post" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Nama Pelanggan</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Produk</label>
                <select name="product_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="" disabled selected>Pilih produk</option>
                    @foreach($produks as $id => $nama)
                        <option value="{{ $id }}" @selected(old('product_id') == $id)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Qty</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Harga</label>
                <input type="number" name="price" value="{{ old('price', 0) }}" min="0" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Total</label>
            <input type="number" name="total" value="{{ old('total', 0) }}" min="0" class="mt-1 w-full rounded border px-3 py-2" required>
            <p class="text-xs text-gray-500 mt-1">Isi manual atau hitung dari Qty × Harga.</p>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('penjualan.index') }}"
               class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection