<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VELGSECONDPLG</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-full bg-gray-50 text-gray-900">
    <header class="border-b bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-2 sm:gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 shrink-0" aria-label="Beranda">
                <img src="{{ asset('images/vsplogo.jpg') }}" alt="Logo VELGSECONDPLG"
                     style="width:160px;height:70px"
                     class="object-contain shrink-0 align-middle">
            </a>

            <form action="{{ route('produk.index') }}" method="get" class="flex-1 hidden md:flex">
                <div class="relative w-full">
                    <input name="q" type="text" placeholder="Cari velg, supplier, pelanggan..."
                           class="w-full rounded-md border-gray-300 pl-10 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
                    </svg>
                </div>
            </form>

            <div class="ml-auto w-full flex items-center gap-3 justify-end">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center justify-center rounded-md border px-3 py-2" aria-label="Keranjang">
                        <svg class="w-5 h-5 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13l-2-8H3"/>
                            <circle cx="9" cy="19" r="1.8"/>
                            <circle cx="17" cy="19" r="1.8"/>
                        </svg>
                        @php
                            $cartKey = auth()->check() ? 'cart_user_'.auth()->id() : 'cart_guest';
                            $cartCount = collect(session($cartKey, session('cart', [])))->sum('qty');
                        @endphp
                        @if($cartCount > 0)
                            <span class="pointer-events-none absolute top-0 right-0 translate-x-[75%] -translate-y-[75%] z-10 min-w-[20px] h-[20px] rounded-full bg-red-600 text-white text-[11px] font-bold ring-2 ring-white flex items-center justify-center px-1 leading-none">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login', ['msg' => 'login-required', 'redirect' => route('cart.index')]) }}" class="relative inline-flex items-center justify-center rounded-md border px-3 py-2" aria-label="Keranjang">
                        <svg class="w-5 h-5 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13l-2-8H3"/>
                            <circle cx="9" cy="19" r="1.8"/>
                            <circle cx="17" cy="19" r="1.8"/>
                        </svg>
                        @php
                            $cartKey = auth()->check() ? 'cart_user_'.auth()->id() : 'cart_guest';
                            $cartCount = collect(session($cartKey, session('cart', [])))->sum('qty');
                        @endphp
                        @if($cartCount > 0)
                            <span class="pointer-events-none absolute top-0 right-0 translate-x-[75%] -translate-y-[75%] z-10 min-w-[20px] h-[20px] rounded-full bg-red-600 text-white text-[11px] font-bold ring-2 ring-white flex items-center justify-center px-1 leading-none">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endauth

                @auth
                    <a href="{{ route('profile.edit') }}"
                       class="text-sm font-medium text-slate-900 hover:text-indigo-600">
                        {{ auth()->user()->name ?? auth()->user()->email ?? 'Profil' }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="rounded-md border px-3 py-1.5 text-sm hover:bg-gray-100">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm hover:text-indigo-600">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm hover:text-indigo-600">Daftar</a>
                @endauth
            </div>

            <button id="navToggle" class="md:hidden inline-flex items-center justify-center rounded-md border p-2">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18"/>
                </svg>
            </button>
        </div>

        @php
            $menu = [
                ['label'=>'Produk','route'=>'produk.index','match'=>'produk.*'],
                ['label'=>'Penjualan','route'=>'penjualan.index','match'=>'penjualan.*'],
                ['label'=>'Pembelian','route'=>'pembelian.index','match'=>'pembelian.*'],
                ['label'=>'Transaksi','route'=>'transaksi.index','match'=>'transaksi.*'],
                ['label'=>'Pembayaran','route'=>'pembayaran.index','match'=>'pembayaran.*'],
                ['label'=>'Stok','route'=>'stok.index','match'=>'stok.*'],
                ['label'=>'Retur','route'=>'retur.index','match'=>'retur.*'],
                ['label'=>'Tukar Tambah','route'=>'tukar-tambah.index','match'=>'tukar-tambah.*'],
                ['label'=>'Pelanggan','route'=>'pelanggan.index','match'=>'pelanggan.*'],
                ['label'=>'Supplier','route'=>'suppliers.index','match'=>'suppliers.*'],
                ['label'=>'Pengguna','route'=>'penggunas.index','match'=>'penggunas.*'],
                ['label'=>'Laporan Transaksi','route'=>'laporan-transaksi.index','match'=>'laporan-transaksi.*'],
            ];
        @endphp

        <nav class="border-t md:border-t-0 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div id="navBar" class="py-2 overflow-x-auto flex gap-3 md:gap-4">
                    @foreach($menu as $item)
                        @php $active = request()->routeIs($item['match']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="shrink-0 rounded-md px-3 py-2 text-sm {{ $active ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <footer class="border-t bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-600">
            © {{ date('Y') }} Velg Second Palembang.
        </div>
    </footer>

    <script>
        const toggle = document.getElementById('navToggle');
        const bar = document.getElementById('navBar');
        if (toggle && bar) {
            toggle.addEventListener('click', () => {
                bar.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>