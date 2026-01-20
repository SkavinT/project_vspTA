@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Edit Pembayaran</h1>

    @php
        $user = auth()->user();
        $isKaryawan = $user && $user->role === 'karyawan';
    @endphp

    <form action="{{ $isKaryawan
        ? route('pembayaran.karyawan.update', $pembayaran)
        : route('pembayaran.update', $pembayaran) }}"
          method="post" enctype="multipart/form-data"
          class="rounded-lg border bg-white p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Order ID</label>
            <input type="number" name="order_id" value="{{ old('order_id', $pembayaran->order_id) }}" readonly
                   class="mt-1 w-full rounded-md border-gray-300">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $pembayaran->nama) }}" readonly
                       class="mt-1 w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="text"
                       value="Rp {{ number_format(old('jumlah', $pembayaran->jumlah), 0, ',', '.') }}"
                       readonly
                       class="mt-1 w-full rounded-md border-gray-300">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Metode</label>
                <input type="text" name="metode" value="{{ old('metode', $pembayaran->metode) }}" readonly
                       class="mt-1 w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($pembayaran->tanggal)->format('Y-m-d')) }}" readonly
                       class="mt-1 w-full rounded-md border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                @foreach(['proses verifikasi','diverifikasi','dikemas','sedang dalam perjalanan','terkirim','dibatalkan'] as $st)
                    <option value="{{ $st }}" @selected(old('status', $pembayaran->status) === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Bukti (jpg/jpeg/png/pdf)</label>
            <input type="file" name="bukti" disabled class="mt-1 w-full rounded-md border-gray-300">
            @if($pembayaran->bukti)
                <p class="mt-2 text-sm">
                    Saat ini: <a href="{{ asset('storage/'.$pembayaran->bukti) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a>
                </p>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('pembayaran.index') }}"
               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">Batal</a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection