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
        use App\Models\Produk;
        use App\Models\Pelanggan;

        $user      = auth()->user();
        $isAdmin   = $user && ($user->role === 'admin');
        $today     = \Carbon\Carbon::today()->toDateString();
        $yourName  = old('customer_name', $user?->name ?? '');
        $produks   = Produk::orderBy('nama')->get(['id','nama','harga']);
        $pelanggans = $isAdmin ? Pelanggan::orderBy('nama')->get(['id','nama']) : collect();
    @endphp

    <form action="{{ route('penjualan.store') }}" method="post" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $today) }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>

            @if($isAdmin)
                <div>
                    <label class="block text-sm font-medium">Pelanggan</label>
                    <select name="pelanggan_id" class="mt-1 w-full rounded border px-3 py-2">
                        <option value="" disabled {{ old('pelanggan_id') ? '' : 'selected' }}>Pilih pelanggan</option>
                        @foreach($pelanggans as $pl)
                            <option value="{{ $pl->id }}" data-name="{{ $pl->nama }}" @selected(old('pelanggan_id') == $pl->id)>{{ $pl->nama }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium">Nama Pelanggan</label>
            <input type="text"
                   name="customer_name"
                   value="{{ $isAdmin ? old('customer_name') : $yourName }}"
                   class="mt-1 w-full rounded border px-3 py-2"
                   {{ $isAdmin ? '' : 'readonly' }}
                   placeholder="{{ $isAdmin ? 'Akan terisi otomatis dari pilihan pelanggan (bisa disunting)' : 'Terisi otomatis dari akun Anda' }}"
                   required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Produk</label>
                <select name="product_id" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="" disabled {{ old('product_id') ? '' : 'selected' }}>Pilih produk</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->harga }}" @selected(old('product_id') == $p->id)>{{ $p->nama }}</option>
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
            <p class="text-xs text-gray-500 mt-1">Total dihitung otomatis dari Qty × Harga (bisa disunting bila perlu).</p>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pelangganSel = document.querySelector('select[name="pelanggan_id"]');
    const customerName = document.querySelector('input[name="customer_name"]');
    const productSel   = document.querySelector('select[name="product_id"]');
    const qty          = document.querySelector('input[name="quantity"]');
    const price        = document.querySelector('input[name="price"]');
    const total        = document.querySelector('input[name="total"]');

    function recalcTotal() {
        const q  = parseFloat(qty?.value || 0);
        const pr = parseFloat(price?.value || 0);
        total.value = Number.isFinite(q * pr) ? Math.round(q * pr) : 0;
    }

    function onPelangganChange() {
        if (!pelangganSel || !customerName) return;
        const opt = pelangganSel.options[pelangganSel.selectedIndex];
        const nm  = opt?.dataset.name || '';
        if (nm) customerName.value = nm;
    }

    function onProductChange() {
        const opt = productSel?.options[productSel.selectedIndex];
        const pr  = parseFloat(opt?.dataset.price || 0);
        if (!Number.isNaN(pr) && price) price.value = pr;
        recalcTotal();
    }

    pelangganSel?.addEventListener('change', onPelangganChange);
    productSel?.addEventListener('change', onProductChange);
    qty?.addEventListener('input', recalcTotal);
    price?.addEventListener('input', recalcTotal);

    onPelangganChange();
    onProductChange();
    recalcTotal();
});
</script>
@endsection