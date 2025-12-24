@extends('layouts.shop')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Keranjang</h1>

    @if(empty($cart))
        <div class="rounded-lg border bg-white p-8 text-center">
            Keranjang masih kosong.
            <a href="{{ route('produk.index') }}" class="inline-block mt-3 text-indigo-600">Belanja sekarang</a>
        </div>
    @else
        <form action="{{ route('cart.update') }}" method="post" class="space-y-6">
            @csrf
            <div class="overflow-x-auto rounded-lg border bg-white">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium">Produk</th>
                            <th class="px-4 py-3 text-right text-sm font-medium">Harga</th>
                            <th class="px-4 py-3 text-center text-sm font-medium">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-medium">Subtotal</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3 flex items-center gap-3">
                                @if($item['image'])
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-14 h-14 object-cover rounded">
                                @else
                                    <div class="w-14 h-14 bg-gray-200 rounded"></div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $item['name'] }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $item['id'] }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="items[{{ $loop->index }}][qty]" value="{{ $item['qty'] }}" min="1" class="w-16 rounded border px-2 py-1 text-center">
                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item['id'] }}">
                            </td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('cart.remove') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t bg-gray-50">
                        <tr>
                            <td class="px-4 py-3 font-medium" colspan="3">Subtotal</td>
                            <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex gap-2">
                    <a href="{{ route('produk.index') }}" class="rounded border px-4 py-2">Lanjut Belanja</a>
                    <form action="{{ route('cart.clear') }}" method="post">
                        @csrf
                        <button type="submit" class="rounded border px-4 py-2 text-red-600">Kosongkan</button>
                    </form>
                </div>
                <button type="submit" class="rounded bg-slate-900 text-white px-4 py-2">Perbarui Qty</button>
            </div>

            <div class="mt-6 rounded-lg border bg-white p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-xl font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                    <button type="button" class="rounded bg-indigo-600 text-white px-5 py-2">Checkout (contoh)</button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection