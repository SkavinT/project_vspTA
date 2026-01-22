@extends('layouts.shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Pengguna</h1>
                <p class="text-gray-600">Daftar pengguna aplikasi.</p>
            </div>
            @if(Route::has('penggunas.create'))
                <a href="{{ route('penggunas.create') }}"
                   class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                    + Tambah Pengguna
                </a>
            @endif
        </div>

        @if($penggunas->count() === 0)
            <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
                Belum ada data pengguna.
            </div>
        @else
            <div class="rounded-lg border bg-white overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Role</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($penggunas as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $p->nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->email }}</td>
                            @php($r = strtolower($p->user_role ?? ($p->role ?? 'guest')))
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border uppercase
                                    @if($r === 'admin') bg-red-50 text-red-700 border-red-200
                                    @elseif($r === 'karyawan' || $r === 'supplier') bg-indigo-50 text-indigo-700 border-indigo-200
                                    @else bg-gray-50 text-gray-700 border-gray-200
                                    @endif">
                                    {{ $r }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <a href="{{ route('penggunas.show', $p) }}"
                                   class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-3 py-1.5 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                                    Detail
                                </a>

                                @if(auth()->user()?->role === 'admin')
                                    <a href="{{ route('penggunas.edit', $p) }}"
                                       class="ml-2 inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-3 py-1.5 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $penggunas->links() }}
            </div>
        @endif
    </div>
@endsection