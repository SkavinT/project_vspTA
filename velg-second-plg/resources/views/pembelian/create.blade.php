@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Pembelian</h1>
        <a href="{{ route('pembelian.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('pembelian.store') }}" method="post" enctype="multipart/form-data" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Supplier</label>
                <select name="supplier_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="" disabled {{ old('supplier_id') ? '' : 'selected' }}>Pilih supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Produk</label>
                <select name="product_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="" disabled {{ old('product_id') ? '' : 'selected' }}>Pilih produk</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->harga }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Harga Modal</label>
                @php
                    $hargaOld = old('harga_modal');
                    $totalOld = old('total');
                @endphp
                <input type="text" id="harga_modal_display"
                       value="{{ $hargaOld !== null ? number_format($hargaOld, 0, ',', '.') : '' }}"
                       class="mt-1 w-full rounded border px-3 py-2" autocomplete="off" required>
                <input type="hidden" name="harga_modal" id="harga_modal"
                       value="{{ $hargaOld !== null ? $hargaOld : 0 }}">
            </div>
            <div>
                <label class="block text-sm font-medium">Jumlah</label>
                <input type="number" min="1" name="jumlah" value="{{ old('jumlah', 1) }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Total</label>
                <input type="text" id="total_display"
                       value="{{ $totalOld !== null ? number_format($totalOld, 0, ',', '.') : '0' }}"
                       class="mt-1 w-full rounded border px-3 py-2" autocomplete="off" required>
                <input type="hidden" name="total" id="total"
                       value="{{ $totalOld !== null ? $totalOld : 0 }}">
                <p class="text-xs text-gray-500 mt-1">Otomatis dihitung dari Harga Modal × Jumlah (bisa disesuaikan).</p>
            </div>
            <div>
                <label class="block text-sm font-medium">Gambar (opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="mt-1 block w-full">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-sm font-medium">Status</label>
                @php $st = old('status', 'dipesan'); @endphp
                <select name="status" class="mt-1 w-full rounded border px-3 py-2">
                    <option value="dipesan"   @selected($st === 'dipesan')>Dipesan</option>
                    <option value="dikirim"   @selected($st === 'dikirim')>Dikirim</option>
                    <option value="diterima"  @selected($st === 'diterima')>Diterima</option>
                    <option value="selesai"   @selected($st === 'selesai')>Selesai</option>
                    <option value="dibatalkan"@selected($st === 'dibatalkan')>Dibatalkan</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" rows="3" class="mt-1 w-full rounded border px-3 py-2">{{ old('keterangan') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('pembelian.index') }}" class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">Simpan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selProd    = document.querySelector('select[name="product_id"]');
    const hargaDisp  = document.getElementById('harga_modal_display');
    const hargaHid   = document.getElementById('harga_modal');
    const jumlah     = document.querySelector('input[name="jumlah"]');
    const totalDisp  = document.getElementById('total_display');
    const totalHid   = document.getElementById('total');

    function parseRupiahInt(str) {
        if (!str) return 0;
        const onlyNum = String(str).replace(/\D/g, ''); // buang semua kecuali angka
        const n = parseInt(onlyNum || '0', 10);
        return isNaN(n) ? 0 : n;
    }

    function formatRupiahInt(n) {
        n = parseInt(n || 0, 10);
        const s = n.toString();
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // 1000000 -> 1.000.000
    }

    function recalc() {
        const h = parseRupiahInt(hargaHid.value);
        const j = parseInt(jumlah.value || 0, 10);
        const t = h * (isNaN(j) ? 0 : j);
        totalHid.value  = t;
        totalDisp.value = formatRupiahInt(t);
    }

    function syncHargaFromDisplay() {
        const num = parseRupiahInt(hargaDisp.value);
        hargaHid.value  = num;
        hargaDisp.value = num ? formatRupiahInt(num) : '';
        recalc();
    }

    selProd && selProd.addEventListener('change', () => {
        const opt = selProd.options[selProd.selectedIndex];
        const pr  = parseRupiahInt(opt?.dataset.price || 0);
        hargaHid.value  = pr;
        hargaDisp.value = pr ? formatRupiahInt(pr) : '';
        recalc();
    });

    hargaDisp && hargaDisp.addEventListener('input', syncHargaFromDisplay);
    hargaDisp && hargaDisp.addEventListener('blur', syncHargaFromDisplay);
    jumlah    && jumlah.addEventListener('input', recalc);
    jumlah    && jumlah.addEventListener('blur', recalc);

    // format awal
    if (hargaDisp && hargaDisp.value) {
        syncHargaFromDisplay();
    } else {
        recalc();
    }
});
</script>
@endsection