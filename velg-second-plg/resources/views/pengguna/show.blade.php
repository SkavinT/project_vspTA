@extends('layouts.shop')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Detail Pengguna</h1>
    <div class="rounded-lg border bg-white p-6 max-w-lg">
        <div class="mb-2"><span class="text-gray-600">Nama:</span> <span class="font-medium">{{ $pengguna->nama }}</span></div>
        <div class="mb-2"><span class="text-gray-600">Email:</span> <span class="font-medium">{{ $pengguna->email }}</span></div>
        <div class="mb-2"><span class="text-gray-600">Role:</span> <span class="font-medium uppercase">{{ $pengguna->role ?? 'guest' }}</span></div>
        <div class="mt-4">
            <a href="{{ route('penggunas.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Kembali</a>
        </div>
    </div>
@endsection