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
                @php
                    $user = auth()->user();
                    $isAdmin = $user && $user->role === 'admin';
                    $defaultName = old('customer_name', $user->name ?? '');
                @endphp

                @if($isAdmin)
                    {{-- Admin boleh mengubah jika perlu --}}
                    <input type="text" name="customer_name" value="{{ $defaultName }}"
                           class="mt-1 w-full rounded border px-3 py-2" required>
                @else
                    {{-- Pelanggan: nama otomatis & tidak bisa diubah --}}
                    <input type="hidden" name="customer_name" value="{{ $defaultName }}">
                    <input type="text" value="{{ $defaultName }}"
                           class="mt-1 w-full rounded border px-3 py-2 bg-gray-50" readonly>
                    <p class="mt-1 text-xs text-gray-500">
                        Nama diambil dari akun yang sedang login.
                    </p>
                @endif
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
                        <option value="{{ $produk->id }}"
                                data-price="{{ $produk->harga }}"
                                @selected(old('produk_id') == $produk->id)>
                            {{ $produk->nama }} - Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Perkiraan Harga Tukar Tambah</label>
                @php
                    $user = auth()->user();
                    $canSetPrice = $user && in_array($user->role, ['admin','karyawan']);
                @endphp

                @if($canSetPrice)
                    @php $priceOld = old('price'); @endphp
                    <input type="text" id="trade_price_display"
                           value="{{ $priceOld !== null ? number_format($priceOld, 0, ',', '.') : '' }}"
                           class="mt-1 w-full rounded border px-3 py-2" autocomplete="off">
                    <input type="hidden" name="price" id="trade_price" value="{{ $priceOld }}">
                    <p class="mt-1 text-xs text-gray-500">
                        Admin/karyawan dapat mengisi nilai tukar tambah di sini.
                    </p>
                @else
                    {{-- Pelanggan: tidak bisa mengubah, hanya mengajukan --}}
                    <input type="hidden" name="price" value="">
                    <input type="text" value="Akan dinilai oleh admin"
                           class="mt-1 w-full rounded border px-3 py-2 bg-gray-50" readonly>
                    <p class="mt-1 text-xs text-gray-500">
                        Nilai tukar tambah akan ditentukan oleh admin/karyawan.
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-sm font-medium">Harga Produk (otomatis)</label>
                <input type="text" id="product_price" class="mt-1 w-full rounded border px-3 py-2 bg-gray-50"
                       readonly>
            </div>
            <div>
                <label class="block text-sm font-medium">Total Selisih yang Harus Dibayar</label>
                <input type="text" id="total_diff" class="mt-1 w-full rounded border px-3 py-2 bg-gray-50"
                       readonly>
                {{-- jika ingin total ikut terkirim ke server --}}
                <input type="hidden" name="total" id="total_hidden">
                <p class="mt-1 text-xs text-gray-500">
                    Total = Harga Produk - Perkiraan Harga Tukar Tambah.
                </p>
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

        @php
            $user = auth()->user();
            $canSetStatus = $user && in_array($user->role, ['admin','karyawan']);
        @endphp

        @if($canSetStatus)
            <input type="hidden" name="status" value="{{ old('status', 'sedang_negosiasi') }}">
        @else
            <input type="hidden" name="status" value="sedang_negosiasi">
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectProduk   = document.querySelector('select[name="produk_id"]');
    const tradeDisp      = document.getElementById('trade_price_display');
    const tradeHid       = document.getElementById('trade_price');
    const inputProdPrice = document.getElementById('product_price');
    const inputTotalDiff = document.getElementById('total_diff');
    const inputTotalHid  = document.getElementById('total_hidden');

    function parseRupiahInt(str) {
        if (!str) return 0;
        const onlyNum = String(str).replace(/\D/g, '');
        const n = parseInt(onlyNum || '0', 10);
        return isNaN(n) ? 0 : n;
    }

    function formatRupiahInt(n) {
        n = parseInt(n || 0, 10);
        const s = n.toString();
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // 11000000 -> 11.000.000
    }

    function getHargaProduk() {
        if (!selectProduk) return 0;
        const opt = selectProduk.options[selectProduk.selectedIndex];
        return parseRupiahInt(opt?.dataset.price || 0);
    }

    function recalc() {
        const hargaProduk = getHargaProduk();
        const tradeIn     = parseRupiahInt(tradeHid ? tradeHid.value : 0);
        const selisih     = Math.max(hargaProduk - tradeIn, 0);

        if (inputProdPrice) inputProdPrice.value = hargaProduk ? formatRupiahInt(hargaProduk) : '';
        if (inputTotalDiff) inputTotalDiff.value = selisih ? formatRupiahInt(selisih) : '0';
        if (inputTotalHid)  inputTotalHid.value  = selisih;
    }

    function syncTradeFromDisplay() {
        if (!tradeDisp || !tradeHid) {
            recalc();
            return;
        }
        const num = parseRupiahInt(tradeDisp.value);
        tradeHid.value  = num;
        tradeDisp.value = num ? formatRupiahInt(num) : '';
        recalc();
    }

    if (selectProduk) {
        selectProduk.addEventListener('change', recalc);
    }
    if (tradeDisp) {
        tradeDisp.addEventListener('input', syncTradeFromDisplay);
        tradeDisp.addEventListener('blur', syncTradeFromDisplay);
    }

    // hitung awal kalau ada nilai lama
    if (tradeDisp && tradeDisp.value) {
        syncTradeFromDisplay();
    } else {
        recalc();
    }
});
</script>
@endpush