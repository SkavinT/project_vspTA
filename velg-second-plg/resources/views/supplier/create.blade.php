@extends('layouts.shop')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Supplier</h1>
        <a href="{{ route('suppliers.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.store') }}" method="post" class="rounded-lg border bg-white p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">Nama</label>
            <input name="name" type="text" class="mt-1 w-full rounded border px-3 py-2" value="{{ old('name') }}" required>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input name="email" type="email" class="mt-1 w-full rounded border px-3 py-2" value="{{ old('email') }}">
            </div>
            <div>
                <label class="block text-sm font-medium">Telepon</label>
                <input name="phone" type="text" class="mt-1 w-full rounded border px-3 py-2" value="{{ old('phone') }}">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Alamat</label>
            <textarea name="address" rows="3" class="mt-1 w-full rounded border px-3 py-2">{{ old('address') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('suppliers.index') }}" class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold hover:bg-indigo-50">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection