@extends('layouts.shop')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">
                Detail Retur {{ $retur->nomor }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Informasi lengkap pengajuan retur pelanggan.
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if(auth()->user()?->role === 'admin' && Route::has('retur.edit'))
                <a href="{{ route('retur.edit', $retur) }}"
                   class="inline-flex items-center gap-1 rounded-md border border-indigo-300 bg-white px-3 py-2 text-xs font-medium text-indigo-600 shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                    Edit
                </a>
            @endif
            <a href="{{ route('retur.index') }}"
               class="inline-flex items-center rounded-md border px-3 py-2 text-xs text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </div>

    <div class="mb-4 rounded-lg border bg-white p-4 sm:p-5 space-y-3 text-sm text-slate-800">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Nomor Retur</p>
                <p class="mt-0.5 font-semibold">{{ $retur->nomor }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Status</p>
                <span class="mt-0.5 inline-flex items-center rounded-md px-2 py-1 text-xs border
                    @if($retur->status === 'approved')
                        bg-green-50 text-green-700 border-green-200
                    @elseif($retur->status === 'rejected')
                        bg-red-50 text-red-700 border-red-200
                    @else
                        bg-yellow-50 text-yellow-700 border-yellow-200
                    @endif">
                    {{ $retur->status }}
                </span>
            </div>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <div>
                <p class="text-xs font-medium text-slate-500">Tanggal</p>
                <p class="mt-0.5">
                    {{ $retur->tanggal?->format('d M Y') }}
                </p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Total</p>
                <p class="mt-0.5 font-semibold text-indigo-700">
                    Rp {{ number_format($retur->total, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Pelanggan</p>
                <p class="mt-0.5">
                    {{ optional($retur->pelanggan)->nama ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Kode Transaksi</p>
                <p class="mt-0.5">
                    {{ $retur->transaksi_kode ?? '—' }}
                </p>
            </div>
        </div>

        <div>
            <p class="text-xs font-medium text-slate-500">Keterangan</p>
            <p class="mt-0.5 whitespace-pre-line">
                {{ $retur->keterangan ?: '—' }}
            </p>
        </div>
    </div>

    <div class="rounded-lg border bg-white p-4 sm:p-5 text-sm">
        <p class="mb-3 text-xs font-medium text-slate-500">Bukti (gambar / video)</p>

        @php
            $files = $retur->bukti_files ?? [];
        @endphp

        @if(empty($files))
            <p class="text-slate-400 text-sm">Tidak ada file bukti yang diunggah.</p>
        @else
            <div class="grid gap-3 sm:grid-cols-3">
                @foreach($files as $path)
                    @php
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4','mov','avi','mkv','webm']);
                    @endphp

                    <div class="border rounded-md p-2 flex flex-col gap-2">
                        @if(!$isVideo)
                            <a href="{{ asset('storage/'.$path) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/'.$path) }}"
                                     alt="Bukti Retur"
                                     class="h-32 w-full rounded object-cover">
                            </a>
                        @else
                            <a href="{{ asset('storage/'.$path) }}" target="_blank"
                               class="flex h-32 w-full items-center justify-center rounded bg-slate-100 text-xs font-medium text-slate-700">
                                Lihat Video
                            </a>
                        @endif
                        <p class="text-[11px] text-slate-500 break-all">
                            {{ $path }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection