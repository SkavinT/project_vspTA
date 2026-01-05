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
    @endphp

    <form action="{{ route('retur.store') }}" method="post" enctype="multipart/form-data" class="rounded-lg border bg-white p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nomor Retur</label>
                <input type="text" name="nomor" value="{{ $defaultNomor }}" class="mt-1 w-full rounded border px-3 py-2" required>
                <p class="text-xs text-gray-500 mt-1">Otomatis terisi, boleh disesuaikan.</p>
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
                @php $st = old('status', 'pending'); @endphp
                <select name="status" class="mt-1 w-full rounded border px-3 py-2" required>
                    <option value="pending"  @selected($st === 'pending')>pending</option>
                    <option value="approved" @selected($st === 'approved')>approved</option>
                    <option value="rejected" @selected($st === 'rejected')>rejected</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Total</label>
                <input type="number" step="0.01" min="0" name="total" value="{{ old('total', 0) }}" class="mt-1 w-full rounded border px-3 py-2" required>
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

        <div class="flex justify-end gap-3">
            <a href="{{ route('retur.index') }}" class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection