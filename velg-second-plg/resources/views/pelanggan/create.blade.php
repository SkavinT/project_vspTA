@extends('layouts.shop')

@section('content')
    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Tambah Pelanggan</h1>
                <p class="text-gray-600">Isi data pelanggan sesuai kebutuhan.</p>
            </div>
            <a href="{{ route('pelanggan.index') }}"
               class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                Kembali
            </a>
        </div>

        <div class="rounded-lg border bg-white p-5">
            @if($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(Route::has('pelanggan.store'))
                <form action="{{ route('pelanggan.store') }}" method="post" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-600">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                               class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600">Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}"
                                   class="mt-1 w-full rounded-md border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   readonly
                                   class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                                   aria-readonly="true">
                            <p class="mt-1 text-xs text-gray-500">Email mengikuti akun yang sedang login.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('pelanggan.index') }}"
                           class="inline-flex items-center rounded-md border border-slate-300 bg-white text-slate-700 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="ml-auto inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                            + Simpan Pelanggan
                        </button>
                    </div>
                </form>
            @else
                <div class="text-sm text-red-700">
                    Route pelanggan.store belum tersedia. Aktifkan route create/store di routes.
                </div>
            @endif
        </div>
    </div>
@endsection