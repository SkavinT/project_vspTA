@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Retur</h1>
        <a href="{{ route('retur.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
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

    @php
        $defaultNomor = old('nomor', 'RTN-' . now()->format('Ymd-His'));
        $today = old('tanggal', now()->toDateString());

        $prefilledKode = old('transaksi_kode', $kode ?? ($transaksi->kode ?? null));
        $prefilledTotal = old('total', $prefillTotal ?? 0);
        $selectedTransaksiId = old('transaksi_id', $transaksi->id ?? null);
    @endphp

    <form action="{{ route('retur.store') }}" method="post" enctype="multipart/form-data" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf

        @if(isset($transaksi))
            <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
        @endif
        @if($prefilledKode)
            <input type="hidden" name="transaksi_kode" value="{{ $prefilledKode }}">
        @endif

        @if($prefilledKode)
            <div>
                <label class="block text-sm font-medium">Kode Transaksi</label>
                <input type="text" value="{{ $prefilledKode }}" class="mt-1 w-full rounded border px-3 py-2 bg-gray-50" readonly>
                <p class="text-xs text-gray-500 mt-1">Retur ini terkait dengan transaksi di atas.</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nomor Retur</label>
                <input type="text" name="nomor" value="{{ $defaultNomor }}"
                       class="mt-1 w-full rounded border px-3 py-2 bg-gray-50"
                       readonly>
                <p class="text-xs text-gray-500 mt-1">Otomatis terisi dan tidak dapat diubah.</p>
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $today }}" class="mt-1 w-full rounded border px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Pelanggan (opsional)</label>
                <select name="customer_id" class="mt-1 w-full rounded border px-3 py-2">
                    <option value="" {{ old('customer_id') ? '' : 'selected' }}>— Tidak pilih —</option>
                    @foreach($pelanggans as $pl)
                        <option value="{{ $pl->id }}" @selected(old('customer_id') == $pl->id)>{{ $pl->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Status</label>
                @php
                    $user = auth()->user();
                    $isAdmin = $user && $user->role === 'admin';
                    $st = old('status', 'pending');
                @endphp

                @if($isAdmin)
                    <select name="status" class="mt-1 w-full rounded border px-3 py-2" required>
                        <option value="pending"  @selected($st === 'pending')>pending</option>
                        <option value="approved" @selected($st === 'approved')>approved</option>
                        <option value="rejected" @selected($st === 'rejected')>rejected</option>
                    </select>
                @else
                    <input type="hidden" name="status" value="pending">
                    <input type="text" value="pending"
                           class="mt-1 w-full rounded border px-3 py-2 bg-gray-50" readonly>
                    <p class="text-xs text-gray-500 mt-1">
                        Status awal selalu pending dan akan diubah oleh admin.
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Total</label>
                <input type="text" id="retur_total_display"
                       value="{{ $prefilledTotal !== null ? number_format($prefilledTotal, 0, ',', '.') : '' }}"
                       class="mt-1 w-full rounded border px-3 py-2" autocomplete="off" required>
                <input type="hidden" name="total" id="retur_total"
                       value="{{ $prefilledTotal ?? 0 }}">
            </div>
            <div>
                <label class="block text-sm font-medium">Bukti (gambar / video, bisa lebih dari 1)</label>
                <input type="file"
                       name="bukti_files[]"
                       accept="image/*,video/*"
                       multiple
                       class="mt-1 block w-full">
                <p class="text-xs text-gray-500 mt-1">
                    Pilih satu atau beberapa file. Format: gambar atau video, maksimum 50MB per file.
                </p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" rows="3" class="mt-1 w-full rounded border px-3 py-2">{{ old('keterangan') }}</textarea>
        </div>

        @if(isset($transaksis) && $transaksis->count())
            <div>
                <label class="block text-sm font-medium">Pilih Kode Transaksi (opsional)</label>
                <select name="transaksi_id" class="mt-1 w-full rounded border px-3 py-2">
                    <option value="" {{ $selectedTransaksiId ? '' : 'selected' }}>— Tidak pilih —</option>
                    @foreach($transaksis as $t)
                        <option value="{{ $t->id }}" @selected($selectedTransaksiId == $t->id)>
                            {{ $t->kode }} — Rp {{ number_format($t->total, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    Pilih kode transaksi yang mau diretur.
                </p>
            </div>
        @endif

        <div class="flex justify-end gap-3">
            <a href="{{ route('retur.index') }}" class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const disp = document.getElementById('retur_total_display');
    const hid  = document.getElementById('retur_total');
    if (!disp || !hid) return;

    function parseRupiahInt(str) {
        if (!str) return 0;
        const onlyNum = String(str).replace(/\D/g, '');
        const n = parseInt(onlyNum || '0', 10);
        return isNaN(n) ? 0 : n;
    }

    function formatRupiahInt(n) {
        n = parseInt(n || 0, 10);
        const s = n.toString();
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function sync() {
        const num = parseRupiahInt(disp.value);
        hid.value  = num;
        disp.value = num ? formatRupiahInt(num) : '';
    }

    if (disp.value) {
        sync();
    }

    disp.addEventListener('input', sync);
    disp.addEventListener('blur', sync);
});
</script>
@endpush