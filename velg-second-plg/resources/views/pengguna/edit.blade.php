@extends('layouts.shop')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Edit Pengguna</h1>
  <form action="{{ route('penggunas.update', $pengguna) }}" method="post" class="max-w-lg rounded-lg border bg-white p-6">
    @csrf @method('PATCH')
    <label class="block text-sm font-medium">Nama</label>
    <input name="nama" value="{{ old('nama', $pengguna->nama) }}" class="mt-1 w-full rounded-md border-gray-300" required>
    <label class="mt-4 block text-sm font-medium">Email</label>
    <input type="email" name="email" value="{{ old('email', $pengguna->email) }}" class="mt-1 w-full rounded-md border-gray-300" required>
    <label class="mt-4 block text-sm font-medium">Password (opsional)</label>
    <input type="password" name="password" class="mt-1 w-full rounded-md border-gray-300">
    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-md border-gray-300" placeholder="Konfirmasi password">
    <label class="mt-4 block text-sm font-medium">Role</label>
    @php $role = old('role', $pengguna->role ?? 'guest'); @endphp
    <select name="role" class="mt-1 w-full rounded-md border-gray-300">
      <option value="guest" @selected($role==='guest')>guest</option>
      <option value="user"  @selected($role==='user')>user</option>
      <option value="staff" @selected($role==='staff')>staff</option>
      <option value="admin" @selected($role==='admin')>admin</option>
    </select>
    <div class="mt-6 flex gap-3">
      <button type="submit" class="rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold hover:bg-indigo-50">Simpan Perubahan</button>
      <a href="{{ route('penggunas.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Kembali</a>
    </div>
  </form>
@endsection