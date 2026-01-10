<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-semibold tracking-tight text-gray-900">
            Selamat Datang Kembali
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Masuk ke akun Anda untuk melanjutkan belanja dan melihat riwayat transaksi.
        </p>
    </div>

    @if(request('msg') === 'login-required')
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
            Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-xs font-medium text-indigo-600 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md"
                    >
                        Lupa password?
                    </a>
                @endif
            </div>

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">
                    Ingat saya
                </span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a
                href="{{ route('register') }}"
                class="font-semibold text-indigo-600 hover:text-indigo-700"
            >
                Daftar sekarang
            </a>
        </p>
    @endif
</x-guest-layout>
