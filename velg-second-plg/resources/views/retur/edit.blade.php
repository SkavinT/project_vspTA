@extends('layouts.shop')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Edit Retur</h1>
            <p class="text-sm text-gray-500">
                Hanya status dan keterangan yang dapat diubah.
            </p>
        </div>
        <a href="{{ route('retur.index') }}" class="text-sm text-indigo-600 hover:underline">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4 rounded-lg border bg-white p-4 text-sm text-gray-700 space-y-1">
        <div><span class="font-medium">Nomor:</span> {{ $retur->nomor }}</div>
        <div><span class="font-medium">Tanggal:</span> {{ $retur->tanggal?->format('d M Y') }}</div>
        <div><span class="font-medium">Pelanggan:</span> {{ optional($retur->pelanggan)->nama ?? '—' }}</div>
        <div><span class="font-medium">Total:</span> Rp {{ number_format($retur->total, 0, ',', '.') }}</div>
    </div>

    <form action="{{ route('retur.update', $retur) }}" method="post" class="rounded-lg border bg-white p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded border px-3 py-2 text-sm">
                @php $st = old('status', $retur->status); @endphp
                <option value="pending"  @selected($st === 'pending')>pending</option>
                <option value="approved" @selected($st === 'approved')>approved</option>
                <option value="rejected" @selected($st === 'rejected')>rejected</option>
            </select>
            <p class="mt-1 text-xs text-gray-500">
                Hanya admin yang boleh mengubah status; pengguna lain akan tetap memakai status sebelumnya.
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      class="mt-1 w-full rounded border px-3 py-2 text-sm">{{ old('keterangan', $retur->keterangan) }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('retur.index') }}"
               class="rounded-md border px-4 py-2 text-sm hover:bg-gray-100">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-indigo-300 bg-white text-indigo-600 px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-50 hover:border-indigo-400">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection