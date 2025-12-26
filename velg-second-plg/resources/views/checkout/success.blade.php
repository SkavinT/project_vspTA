@extends('layouts.shop')

@section('content')
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Pesanan Berhasil</h1>

        @if(!$order)
            <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
                Tidak ada pesanan untuk ditampilkan.
            </div>
            <div class="mt-4">
                <a href="{{ route('produk.index') }}" class="text-indigo-600">Kembali belanja</a>
            </div>
        @else
            <div class="rounded-lg border bg-white p-5">
                <div class="mb-4 text-sm text-gray-600">Nomor Pesanan: <span class="font-semibold">{{ $order['id'] }}</span></div>
                <div class="mb-2 text-sm text-gray-600">
                    Status: <span class="font-semibold">{{ $order['status'] ?? 'proses verifikasi' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="font-semibold mb-2">Penerima</h2>
                        <div class="text-sm text-gray-700">{{ $order['customer']['nama'] }}</div>
                        <div class="text-sm text-gray-700">{{ $order['customer']['email'] }}</div>
                        @if(!empty($order['customer']['telepon']))
                            <div class="text-sm text-gray-700">{{ $order['customer']['telepon'] }}</div>
                        @endif
                        <div class="text-sm text-gray-700 mt-2">{{ $order['customer']['alamat'] }}</div>
                    </div>
                    <div>
                        <h2 class="font-semibold mb-2">Ringkasan</h2>
                        @foreach($order['items'] as $i)
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-gray-700">{{ $i['name'] }}</div>
                                <div class="text-gray-700">{{ (int)$i['qty'] }} × Rp {{ number_format((float)$i['price'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                        <div class="mt-3 border-t pt-2 flex items-center justify-between">
                            <div class="text-sm text-gray-600">Total</div>
                            <div class="text-lg font-bold text-indigo-700">Rp {{ number_format((float)$order['total'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($order['bukti']))
              <div class="mt-4">
                <h2 class="font-semibold mb-2">Bukti</h2>
                <img src="{{ asset('storage/'.$order['bukti']) }}" alt="Bukti pembayaran" class="max-h-64 rounded border">
              </div>
            @endif

            <div class="mt-4 flex items-center gap-3">
                <a href="{{ route('produk.index') }}"
                   class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                    Belanja Lagi
                </a>
                <a href="{{ route('transaksi.index') }}"
                   class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                    Lihat Transaksi
                </a>
            </div>
        @endif
    </div>
@endsection