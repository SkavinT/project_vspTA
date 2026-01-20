@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Produk</h1>
        <a href="{{ route('produk.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
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

    <form action="{{ route('produk.store') }}" method="post" enctype="multipart/form-data"
          class="rounded-lg border bg-white p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium">Nama Produk</label>
            <input type="text" name="nama" value="{{ old('nama') }}"
                   class="mt-1 w-full rounded border px-3 py-2" required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Harga</label>
                <input type="text" id="harga_display"
                       value="{{ old('harga') ? number_format(old('harga'), 0, ',', '.') : '' }}"
                       class="mt-1 w-full rounded border px-3 py-2" autocomplete="off" required>
                <input type="hidden" name="harga" id="harga_hidden" value="{{ old('harga') }}">
            </div>
            <div>
                <label class="block text-sm font-medium">Stok</label>
                <input type="number" name="stok" min="0"
                       value="{{ old('stok', 0) }}"
                       class="mt-1 w-full rounded border px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                      class="mt-1 w-full rounded border px-3 py-2">{{ old('deskripsi') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Foto Produk (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="mt-1 block w-full">
            <p class="text-xs text-gray-500 mt-1">Maksimal 2MB.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('produk.index') }}"
               class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">Batal</a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const disp = document.getElementById('harga_display');
        const hid  = document.getElementById('harga_hidden');

        if (!disp || !hid) return;

        function formatRupiah(angka) {
            const onlyNum = (angka || '').replace(/\D/g, '');
            if (!onlyNum) return '';
            return onlyNum.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function syncFromDisplay() {
            const onlyNum = (disp.value || '').replace(/\D/g, '');
            disp.value = formatRupiah(disp.value);
            hid.value  = onlyNum || '';
        }

        // format awal jika ada nilai lama
        if (disp.value) {
            syncFromDisplay();
        }

        disp.addEventListener('input', syncFromDisplay);
        disp.addEventListener('blur', syncFromDisplay);
    });
</script>
@endpush