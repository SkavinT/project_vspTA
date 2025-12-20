@extends('layouts.shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Data Supplier</h1>
            <p class="text-gray-600">Daftar supplier yang terdaftar.</p>
        </div>
        @if(Route::has('suppliers.create'))
            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Tambah Supplier
            </a>
        @endif
    </div>

    @if($suppliers->count() === 0)
        <div class="rounded-lg border bg-white p-8 text-center text-gray-600">
            Belum ada data supplier.
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
                    @foreach($suppliers as $supplier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $supplier->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $supplier->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $supplier->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 line-clamp-1">{{ $supplier->address ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ optional($supplier->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if(Route::has('suppliers.show'))
                                    <a href="{{ route('suppliers.show', $supplier) }}"
                                       class="rounded-md border px-3 py-1.5 hover:bg-gray-100">
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $suppliers->links() }}
        </div>
    @endif
@endsection