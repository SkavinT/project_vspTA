<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VELGSECONDPLG</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-full bg-gradient-to-b from-sky-100 via-indigo-50 to-white text-gray-900">
    <header class="bg-white/80 backdrop-blur border-b border-slate-200/70">
        <div class="w-full px-4 sm:px-6 lg:px-10 space-y-4">

            {{-- BAR ATAS: logo + search + action --}}
            <div class="flex items-center gap-3 sm:gap-4 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 shrink-0" aria-label="Beranda">
                    <img src="{{ asset('images/vsplogo.jpg') }}" alt="Logo VELGSECONDPLG"
                         style="width:160px;height:70px"
                         class="object-contain shrink-0 align-middle">
                </a>

                <form action="{{ route('produk.index') }}" method="get" class="flex-1 hidden md:flex">
                    <div class="relative w-full">
                        <input name="q" type="text" placeholder="Cari velg, supplier, pelanggan..."
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
                        </svg>
                    </div>
                </form>

                <div class="ml-auto w-full flex items-center justify-end gap-2 sm:gap-3">
                    @auth
                        {{-- Tombol keranjang --}}
                        <a href="{{ route('cart.index') }}"
                           class="relative inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-gray-50 transition"
                           aria-label="Keranjang">
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
                        <a href="{{ route('login', ['msg' => 'login-required', 'redirect' => route('cart.index')]) }}"
                           class="relative inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-gray-50 transition"
                           aria-label="Keranjang">
                            <svg class="w-5 h-5 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13l-2-8H3"/>
                                <circle cx="9" cy="19" r="1.8"/>
                                <circle cx="17" cy="19" r="1.8"/>
                            </svg>
                        </a>
                    @endauth

                    @auth
                        <a href="{{ route('profile.edit') }}"
                           class="hidden sm:inline-flex items-center rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-900 hover:bg-gray-50 transition">
                            {{ auth()->user()->name ?? auth()->user()->email ?? 'Profil' }}
                        </a>

                        {{-- Profil: chip indigo muda --}}
                        <a href="{{ route('profile.edit') }}"
                           class="hidden sm:inline-flex items-center rounded-xl bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-700 hover:bg-indigo-100 transition">
                            {{ auth()->user()->name ?? auth()->user()->email ?? 'Profil' }}
                        </a>

                        {{-- Keluar: tombol outline indigo --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center rounded-xl border border-indigo-500 px-3 py-1.5 text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 transition">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Masuk
                        </a>

                        {{-- Daftar: tombol indigo solid --}}
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center rounded-xl bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition">
                            Daftar
                        </a>
                    @endauth

                    <button id="navToggle"
                            class="md:hidden inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-2 shadow-sm hover:bg-gray-50 transition">
                        <svg class="w-6 h-6 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M3 12h18M3 18h18"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- MENU NAVIGASI SEPERTI FILTER BAR --}}
            @php
                $user       = auth()->user();
                $role       = $user?->role;
                $isAuth     = auth()->check();
                $isAdmin    = $role === 'admin';
                $isKaryawan = $role === 'karyawan';
                $isSupplier = $role === 'supplier';

                $menu = [
                    // Produk = icon kotak produk
                    ['label' => 'Produk',            'route' => 'produk.index',            'match' => 'produk.*',            'show' => true,                              'icon' => 'box'],

                    // Penjualan = keranjang keluar
                    ['label' => 'Penjualan',         'route' => 'penjualan.index',         'match' => 'penjualan.*',         'show' => ($isAdmin || $isKaryawan),         'icon' => 'cart-up'],

                    // Pembelian = keranjang masuk
                    ['label' => 'Pembelian',         'route' => 'pembelian.index',         'match' => 'pembelian.*',         'show' => ($isAdmin || $isSupplier),         'icon' => 'cart-down'],

                    // Stok = grid/daftar stok
                    ['label' => 'Stok',              'route' => 'stok.index',              'match' => 'stok.*',              'show' => ($isAdmin || $isKaryawan || $isSupplier), 'icon' => 'grid'],

                    // Retur = panah putar balik
                    ['label' => 'Retur',             'route' => 'retur.index',             'match' => 'retur.*',             'show' => $isAdmin,                           'icon' => 'u-turn'],

                    // Tukar Tambah = icon swap
                    ['label' => 'Tukar Tambah',      'route' => 'tukar-tambah.index',      'match' => 'tukar-tambah.*',      'show' => $isAdmin,                           'icon' => 'swap'],

                    // Transaksi = struk
                    ['label' => 'Transaksi',         'route' => 'transaksi.index',         'match' => 'transaksi.*',         'show' => ($isAuth && !$isSupplier),          'icon' => 'receipt'],

                    // Pembayaran = kartu
                    ['label' => 'Pembayaran',        'route' => 'pembayaran.index',        'match' => 'pembayaran.*',        'show' => ($isAuth && !$isSupplier),          'icon' => 'card'],

                    // Pelanggan = user tunggal
                    ['label' => 'Pelanggan',         'route' => 'pelanggan.index',         'match' => 'pelanggan.*',         'show' => ($isAuth && !$isSupplier),          'icon' => 'user'],

                    // Supplier = truk / pengiriman
                    ['label' => 'Supplier',          'route' => 'suppliers.index',         'match' => 'suppliers.*',         'show' => $isAdmin,                           'icon' => 'truck'],

                    // Pengguna = banyak user
                    ['label' => 'Pengguna',          'route' => 'penggunas.index',         'match' => 'penggunas.*',         'show' => $isAdmin,                           'icon' => 'users'],

                    // Laporan Transaksi = chart
                    ['label' => 'Laporan Transaksi', 'route' => 'laporan-transaksi.index', 'match' => 'laporan-transaksi.*', 'show' => $isAdmin,                           'icon' => 'chart'],
                ];
            @endphp

            <nav class="rounded-2xl bg-white/90 shadow-md">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div id="navBar"
                         class="py-2 flex items-center justify-start md:justify-center gap-2 sm:gap-3 md:gap-4 overflow-x-auto">
                        @foreach($menu as $item)
                            @if($item['show'])
                                @php
                                    $active = request()->routeIs($item['match']);
                                    $icon   = $item['icon'] ?? 'dot';
                                @endphp

                                <a href="{{ route($item['route']) }}"
                                   class="shrink-0 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs sm:text-sm font-medium whitespace-nowrap transition
                                          {{ $active ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50' }}">
                                    <span class="w-4 h-4 text-current">
                                        @switch($icon)
                                            @case('grid')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <rect x="3" y="3" width="5" height="5" rx="1.2"/>
                                                    <rect x="12" y="3" width="5" height="5" rx="1.2"/>
                                                    <rect x="3" y="12" width="5" height="5" rx="1.2"/>
                                                    <rect x="12" y="12" width="5" height="5" rx="1.2"/>
                                                </svg>
                                                @break

                                            @case('cart-up')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M3 4h2l1.2 7h8.6l1.2-4H6" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 2l3-3m0 0 3 3m-3-3v7" transform="translate(-0.5,4)" />
                                                </svg>
                                                @break

                                            @case('cart-down')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M3 4h2l1.2 7h8.6l1.2-4H6" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 2l3 3m0 0 3-3m-3 3V-1" transform="translate(-0.5,9)" />
                                                </svg>
                                                @break

                                            @case('box')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M4 6l6-3 6 3-6 3-6-3z"/>
                                                    <path d="M4 6v8l6 3 6-3V6"/>
                                                </svg>
                                                @break

                                            @case('u-turn')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M10 16V7a3 3 0 0 0-3-3 3 3 0 0 0-3 3v2" stroke-linecap="round"/>
                                                    <path d="M2 9l2 2 2-2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                @break

                                            @case('swap')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M5 4h9l-2-2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 16H6l2 2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                @break

                                            @case('receipt')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M6 3h8v14l-2-1-2 1-2-1-2 1V3z"/>
                                                    <path d="M8 7h4M8 10h3"/>
                                                </svg>
                                                @break

                                            @case('card')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <rect x="3" y="4" width="14" height="10" rx="2"/>
                                                    <path d="M3 7h14"/>
                                                    <path d="M7 11h3"/>
                                                </svg>
                                                @break

                                            @case('user')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <circle cx="10" cy="7" r="3"/>
                                                    <path d="M4 16c1.3-2 2.9-3 6-3s4.7 1 6 3"/>
                                                </svg>
                                                @break

                                            @case('truck')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <rect x="2" y="5" width="9" height="7" rx="1"/>
                                                    <path d="M11 8h3l2 2v2h-2.5"/>
                                                    <circle cx="6" cy="15" r="1.4"/>
                                                    <circle cx="14" cy="15" r="1.4"/>
                                                </svg>
                                                @break

                                            @case('users')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <circle cx="7" cy="8" r="2.5"/>
                                                    <circle cx="13" cy="7" r="2"/>
                                                    <path d="M3 15c.7-2 1.9-3 4-3s3.3 1 4 3"/>
                                                    <path d="M11 14c.4-1.5 1.3-2.3 2.7-2.3.8 0 1.5.2 2.3 1"/>
                                                </svg>
                                                @break

                                            @case('chart')
                                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path d="M4 4v12h12"/>
                                                    <rect x="6" y="9" width="2" height="4"/>
                                                    <rect x="10" y="7" width="2" height="6"/>
                                                    <rect x="14" y="5" width="2" height="8"/>
                                                </svg>
                                                @break

                                            @default
                                                <svg viewBox="0 0 6 6" fill="currentColor">
                                                    <circle cx="3" cy="3" r="2"/>
                                                </svg>
                                        @endswitch
                                    </span>

                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </nav>

        </div>
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

    @stack('scripts')
</body>
</html>