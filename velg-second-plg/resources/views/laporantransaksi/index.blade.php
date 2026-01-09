@extends('layouts.shop')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Laporan Transaksi</h1>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan penjualan, tukar tambah, pembelian, dan keuntungan usaha Anda.
            </p>
        </div>
    </div>

    {{-- Filter tanggal --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
        <form method="GET" class="grid gap-4 sm:grid-cols-[repeat(3,minmax(0,1fr))] items-end">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Mulai</label>
                <input type="date" name="mulai"
                       value="{{ $mulai }}"
                       class="block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Selesai</label>
                <input type="date" name="selesai"
                       value="{{ $selesai }}"
                       class="block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex gap-2">
                <button class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                    Filter
                </button>
                <a href="{{ route('laporan-transaksi.index') }}"
                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan angka --}}
    <div class="grid gap-4 md:grid-cols-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Penjualan</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Tukar Tambah (Disetujui)</p>
            <p class="mt-2 text-2xl font-semibold text-sky-600">
                Rp {{ number_format($totalTukarTambah, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Pembelian Supplier</p>
            <p class="mt-2 text-2xl font-semibold text-rose-600">
                Rp {{ number_format($totalPembelian, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Keuntungan</p>
            <p class="mt-2 text-2xl font-semibold {{ $totalKeuntungan >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">
                Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">

        {{-- Grafik penjualan per bulan --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-900">Grafik Penjualan per Bulan</h2>
                <span class="text-xs text-slate-400">Rp</span>
            </div>
            <div class="h-64">
                <canvas id="chart-penjualan-bulanan" class="w-full h-full"></canvas>
            </div>
            @if($penjualanBulanan->isEmpty())
                <p class="mt-4 text-xs text-slate-500">
                    Belum ada data penjualan untuk ditampilkan.
                </p>
            @endif
        </div>

        {{-- Tabel penjualan ringkas --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-900">Penjualan Terbaru</h2>
                <span class="text-xs text-slate-500">
                    {{ $penjualans->count() }} transaksi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b bg-slate-50">
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Tanggal</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Produk</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Customer</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-slate-600">Qty</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-slate-600">Harga</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-slate-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse ($penjualans as $p)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-3 py-2 text-slate-800">
                                {{ $p->tanggal?->format('Y-m-d') }}
                            </td>
                            <td class="px-3 py-2 text-slate-800">
                                {{ $p->produk->nama ?? $p->product_id }}
                            </td>
                            <td class="px-3 py-2 text-slate-800">
                                {{ $p->customer_name }}
                            </td>
                            <td class="px-3 py-2 text-right text-slate-800">
                                {{ $p->quantity }}
                            </td>
                            <td class="px-3 py-2 text-right text-slate-800">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2 text-right font-semibold text-indigo-700">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-sm text-slate-500">
                                Belum ada data penjualan.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx  = document.getElementById('chart-penjualan-bulanan')?.getContext('2d');
    if (ctx) {
        const labels = @json($penjualanBulanan->pluck('bulan'));
        const data   = @json($penjualanBulanan->pluck('total'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: data,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,0.08)',
                    tension: 0.25,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#4f46e5',
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
</script>
@endpush