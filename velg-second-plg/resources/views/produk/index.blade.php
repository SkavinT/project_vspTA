@extends('layouts.shop')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold">Produk Terbaru</h1>
        <p class="text-gray-600 mt-1">Temukan velg second berkualitas dengan harga terbaik.</p>
    </div>

    @if($produks->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada produk yang tersedia.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($produks as $produk)
                @include('produk.product-card', ['produk' => $produk])
            @endforeach
        </div>

        <div class="mt-8">
            {{ $produks->links() }}
        </div>
    @endif
@endsection