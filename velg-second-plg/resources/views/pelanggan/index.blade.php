@extends('layouts.shop')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Pelanggan</h1>
            <p class="text-gray-600">Daftar pelanggan terdaftar.</p>
        </div>
        @if(Route::has('pelanggan.create'))
            <a href="{{ route('pelanggan.create') }}"
               class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                + Tambah Data Pelanggan
            </a>
        @endif
    </div>

    @if($pelanggans->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data pelanggan.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Alamat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Dibuat</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pelanggans as $pelanggan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $pelanggan->nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pelanggan->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pelanggan->telepon ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 line-clamp-1">{{ $pelanggan->alamat ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ optional($pelanggan->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center justify-end gap-1.5 sm:gap-2">
                                    @if(Route::has('pelanggan.edit'))
                                        <a href="{{ route('pelanggan.edit', $pelanggan) }}"
                                           class="inline-flex items-center rounded-md border border-indigo-300 bg-indigo-50 text-indigo-700 px-2.5 py-1.5 text-xs font-semibold shadow-sm hover:bg-indigo-100 hover:border-indigo-400">
                                            Edit
                                        </a>
                                    @endif

                                    @if(Route::has('pelanggan.destroy'))
                                        <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="post"
                                              onsubmit="return confirm('Yakin hapus pelanggan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-red-300 bg-red-50 text-red-700 px-2.5 py-1.5 text-xs font-semibold shadow-sm hover:bg-red-100 hover:border-red-400">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $pelanggans->links() }}
        </div>
    @endif
</div>
@endsection