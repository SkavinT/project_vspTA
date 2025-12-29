@extends('layouts.shop')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Supplier</h1>
    @if(auth()->user()?->role === 'admin' && Route::has('suppliers.create'))
        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
           aria-label="Tambah Supplier">
            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah <span class="hidden sm:inline">&nbsp;Supplier</span>
        </a>
    @endif
</div>

@if($suppliers->count() === 0)
    <div class="rounded-lg border bg-white p-6 text-center text-gray-600">
        Belum ada supplier.
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
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($suppliers as $supplier)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ $supplier->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $supplier->email ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $supplier->phone ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $supplier->address ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-right">
                        @if(Route::has('suppliers.show'))
                            <a href="{{ route('suppliers.show', $supplier) }}"
                               class="rounded-md border px-3 py-1.5 hover:bg-gray-100">Detail</a>
                        @endif
                        @if(Route::has('suppliers.edit'))
                            <a href="{{ route('suppliers.edit', $supplier) }}"
                               class="rounded-md border px-3 py-1.5 hover:bg-gray-100">Edit</a>
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