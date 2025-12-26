@extends('layouts.shop')

@section('content')
    @php
        $order = session('last_order');

        $nama    = $order['customer']['nama']   ?? (auth()->user()->name ?? '');
        $email   = $order['customer']['email']  ?? (auth()->user()->email ?? '');
        $alamat  = $order['customer']['alamat'] ?? '';
        $jumlah  = (float)($order['total'] ?? 0);
        $metode  = $order['metode'] ?? 'cod';
        $tanggal = now()->toDateString();

        // Convert order code like "ORD-20251226-ABCDEF" to numeric for controller validation
        $orderCode     = $order['id'] ?? null;
        $orderNumericId= $orderCode ? preg_replace('/\D+/', '', $orderCode) : '';
        $defaultStatus = old('status', 'proses verifikasi');
    @endphp

    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Tambah Pembayaran</h1>
                <p class="text-gray-600">Data diambil dari checkout; hanya status bisa diubah.</p>
            </div>
            <a href="{{ route('pembayaran.index') }}"
               class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                Kembali
            </a>
        </div>

        <div class="rounded-lg border bg-white p-5">
            @if(!$order)
                <div class="mb-4 rounded-md border border-yellow-200 bg-yellow-50 p-3 text-yellow-800 text-sm">
                    Tidak ada data checkout di sesi. Silakan kembali ke checkout untuk membuat pesanan.
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pembayaran.store') }}" method="post" class="space-y-4">
                @csrf

                <!-- Hidden fields to submit required values -->
                <input type="hidden" name="order_id" value="{{ $orderNumericId }}">
                <input type="hidden" name="nama"     value="{{ $nama }}">
                <input type="hidden" name="jumlah"   value="{{ $jumlah }}">
                <input type="hidden" name="metode"   value="{{ $metode }}">
                <input type="hidden" name="tanggal"  value="{{ $tanggal }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600">Nama</label>
                        <input type="text" value="{{ $nama }}" readonly
                               class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                               aria-readonly="true">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Email</label>
                        <input type="email" value="{{ $email }}" readonly
                               class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                               aria-readonly="true">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600">Alamat</label>
                        <textarea rows="2" readonly
                                  class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                                  aria-readonly="true">{{ $alamat }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Order (kode)</label>
                        <input type="text" value="{{ $orderCode ?? '—' }}" readonly
                               class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                               aria-readonly="true">
                        <p class="mt-1 text-xs text-gray-500">Disimpan sebagai ID numerik: {{ $orderNumericId ?: '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Jumlah</label>
                        <input type="text" value="Rp {{ number_format($jumlah, 0, ',', '.') }}" readonly
                               class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                               aria-readonly="true">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Metode</label>
                        <input type="text" value="{{ strtoupper($metode) }}" readonly
                               class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                               aria-readonly="true">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Tanggal</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}" readonly
                               class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2"
                               aria-readonly="true">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600">Status</label>
                        <select name="status" class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="proses verifikasi" @selected($defaultStatus==='proses verifikasi')>Proses Verifikasi</option>
                            <option value="diverifikasi" @selected($defaultStatus==='diverifikasi')>Diverifikasi</option>
                            <option value="dikemas" @selected($defaultStatus==='dikemas')>Dikemas</option>
                            <option value="sedang dalam perjalanan" @selected($defaultStatus==='sedang dalam perjalanan')>Sedang Dalam Perjalanan</option>
                            <option value="terkirim" @selected($defaultStatus==='terkirim')>Terkirim</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hanya status yang dapat diubah.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('pembayaran.index') }}"
                       class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="ml-auto inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                        + Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection