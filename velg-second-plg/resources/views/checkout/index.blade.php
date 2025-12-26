@extends('layouts.shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Checkout</h1>

        @if($cart->isEmpty())
            <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
                Keranjang kosong. <a href="{{ route('produk.index') }}" class="text-indigo-600">Belanja sekarang</a>.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 rounded-lg border bg-white p-5">
                    @if($errors->any())
                        <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-red-700 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('checkout.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600">Nama</label>
                                <input id="nama" name="nama"
                                       value="{{ old('nama', ($defaultPelanggan->nama ?? ($user->name ?? ''))) }}" required
                                       class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600">Email</label>
                                <input id="email" type="email" name="email"
                                       value="{{ old('email', ($defaultPelanggan->email ?? ($user->email ?? ''))) }}" required readonly
                                       class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 bg-gray-50 text-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600">Telepon</label>
                                <input id="telepon" name="telepon"
                                       value="{{ old('telepon', ($defaultPelanggan->telepon ?? '')) }}"
                                       class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600">Metode Pembayaran</label>
                                <select name="metode" id="metode" class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="cod" @selected(old('metode')==='cod')>Bayar di Tempat: Cash / EDC BCA</option>
                                    <option value="transfer" @selected(old('metode')==='transfer')>Transfer</option>
                                    <option value="qris" @selected(old('metode')==='qris')>QRIS</option>
                                </select>
                            </div>

                            <input type="hidden" name="status" value="proses verifikasi">

                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600">Alamat (dari Pelanggan)</label>
                                @if(($pelanggans ?? collect())->isEmpty())
                                    <div class="mt-1 text-sm text-red-600">
                                        Data Pelanggan belum ada untuk akun ini.
                                        <a href="{{ route('pelanggan.create') }}" class="text-indigo-600 underline">Tambah Pelanggan</a>
                                    </div>
                                @else
                                    <select name="pelanggan_id" id="pelanggan_id"
                                            class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @foreach($pelanggans as $pl)
                                            <option value="{{ $pl->id }}"
                                                    data-nama="{{ $pl->nama }}"
                                                    data-email="{{ $pl->email }}"
                                                    data-telepon="{{ $pl->telepon }}"
                                                    @selected(old('pelanggan_id', $defaultPelanggan->id ?? null) == $pl->id)>
                                                {{ $pl->nama }} — {{ $pl->alamat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Ubah alamat di menu Pelanggan jika perlu.</p>
                                @endif
                            </div>
                        </div>

                        <!-- QRIS block (shown when metode = qris) -->
                        <div id="qris-block" class="mt-3 rounded-lg border bg-indigo-50 p-4 hidden">
                            <div class="flex items-start gap-4">
                                <img src="{{ asset('images/qris.jpeg') }}" alt="QRIS" class="w-40 h-40 object-contain rounded border bg-white">
                                <div class="text-sm text-gray-700">
                                    <div class="font-semibold mb-1">Pembayaran via QRIS</div>
                                    <p>Silakan scan barcode QRIS untuk melakukan pembayaran. Setelah pembayaran, unggah bukti pada form di bawah.</p>
                                    <p class="mt-2 text-xs text-gray-500">Letakkan file QRIS di: <span class="font-mono">public/images/qris.png</span></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600">Catatan</label>
                            <textarea name="catatan" rows="2"
                                      class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600">Bukti Pembayaran (opsional)</label>
                            <input type="file" name="bukti" accept="image/*"
                                   class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Unggah foto bukti transfer/QRIS setelah pembayaran.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('cart.index') }}"
                               class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                                Kembali ke Keranjang
                            </a>
                            <button type="submit"
                                    class="ml-auto inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                                Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-lg border bg-white p-5">
                    <h2 class="text-lg font-semibold mb-3">Ringkasan Pesanan</h2>
                    <div class="space-y-3">
                        @foreach($cart as $item)
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-gray-700">{{ $item['name'] }}</div>
                                <div class="text-gray-700">
                                    {{ (int)$item['qty'] }} × Rp {{ number_format((float)$item['price'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 border-t pt-3 flex items-center justify-between">
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-lg font-bold text-indigo-700">Rp {{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Simple toggle: show QRIS block when metode = qris -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          const metode = document.getElementById('metode');
          const qris   = document.getElementById('qris-block');
          const pelSel = document.getElementById('pelanggan_id');
          const nama   = document.getElementById('nama');
          const email  = document.getElementById('email');
          const telp   = document.getElementById('telepon');

          function toggleQris() {
            qris.classList.toggle('hidden', metode.value !== 'qris');
          }
          function applyPelanggan() {
            const opt = pelSel.options[pelSel.selectedIndex];
            if (!opt) return;
            nama.value  = opt.dataset.nama || '';
            email.value = opt.dataset.email || '';
            telp.value  = opt.dataset.telepon || '';
          }

          toggleQris();
          applyPelanggan();

          metode.addEventListener('change', toggleQris);
          pelSel.addEventListener('change', applyPelanggan);
        });
    </script>
@endsection