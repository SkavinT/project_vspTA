@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Pembelian</h1>
        <a href="{{ route('pembelian.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('pembelian.update', $pembelian) }}" method="post" enctype="multipart/form-data" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $pembelian->tanggal?->format('Y-m-d')) }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Supplier</label>
                <select name="supplier_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" @selected(old('supplier_id', $pembelian->supplier_id) == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Produk</label>
                <select name="product_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->harga }}" @selected(old('product_id', $pembelian->product_id) == $p->id)>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Harga Modal</label>
                <input type="number" step="0.01" min="0" name="harga_modal" value="{{ old('harga_modal', $pembelian->harga_modal) }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Jumlah</label>
                <input type="number" min="1" name="jumlah" value="{{ old('jumlah', $pembelian->jumlah) }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Total</label>
                <input type="number" step="0.01" min="0" name="total" value="{{ old('total', $pembelian->total) }}" class="mt-1 w-full rounded border px-3 py-2" required>
                <p class="text-xs text-gray-500 mt-1">Otomatis dihitung dari Harga Modal × Jumlah (bisa disesuaikan).</p>
            </div>
            <div>
                <label class="block text-sm font-medium">Gambar (opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="mt-1 block w-full">
                @if($pembelian->gambar)
                    <p class="mt-2 text-sm">Saat ini:
                        <img src="{{ asset('storage/'.$pembelian->gambar) }}" alt="gambar" class="h-12 w-12 object-cover rounded inline-block">
                    </p>
                @endif
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" rows="3" class="mt-1 w-full rounded border px-3 py-2">{{ old('keterangan', $pembelian->keterangan) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('pembelian.index') }}" class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">Simpan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selProd  = document.querySelector('select[name="product_id"]');
    const harga    = document.querySelector('input[name="harga_modal"]');
    const jumlah   = document.querySelector('input[name="jumlah"]');
    const total    = document.querySelector('input[name="total"]');

    function recalc() {
        const h = parseFloat(harga.value || 0);
        const j = parseInt(jumlah.value || 0, 10);
        const t = h * j;
        total.value = Number.isFinite(t) ? t.toFixed(2) : 0;
    }
    selProd?.addEventListener('change', () => {
        const opt = selProd.options[selProd.selectedIndex];
        const pr = parseFloat(opt?.dataset.price || 0);
        if (!Number.isNaN(pr)) harga.value = pr.toFixed(2);
        recalc();
    });
    harga?.addEventListener('input', recalc);
    jumlah?.addEventListener('input', recalc);
    recalc();
});
</script>
@endsection