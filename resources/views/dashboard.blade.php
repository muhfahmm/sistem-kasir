@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Top Bar Title -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Dashboard Overview</h2>
            <p class="text-xs text-slate-500">Ringkasan penjualan, stok barang, dan performa toko hari ini.</p>
        </div>
    </div>

    <!-- Cards Grid (Light Mode) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Omset Hari Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Omset Hari Ini</span>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($omsetHariIni, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <span class="text-emerald-600 font-bold"><i class="fa-solid fa-arrow-up"></i> {{ $totalTransaksiHariIni }} Transaksi</span> selesai
            </div>
        </div>

        <!-- Card 2: Total Produk -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Produk</span>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalProduk }} <span class="text-xs font-normal text-slate-400">Item</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-500">
                Terdaftar di sistem database
            </div>
        </div>

        <!-- Card 3: Total Unit Stok -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Stok Unit</span>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStok) }} <span class="text-xs font-normal text-slate-400">Unit</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-500">
                Siap dijual di kasir
            </div>
        </div>

        <!-- Card 4: Low Stock Alert -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Stok Menipis</span>
                    <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ $stokMenipis->count() }} <span class="text-xs font-normal text-slate-400">Produk</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-rose-600 font-semibold">
                Perlu restok ulang segera
            </div>
        </div>
    </div>

    <!-- Layout Grid 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tabel Transaksi Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> Transaksi Terakhir Hari Ini
                </h3>
                <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No. Nota</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksiTerbaru as $trx)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $trx->no_nota }}</td>
                            <td class="py-3 px-4 font-bold text-emerald-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $trx->metode_pembayaran }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">{{ $trx->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Belum ada transaksi hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Warning Low Stock Box -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-box text-rose-500"></i> Peringatan Stok Menipis
            </h3>
            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                @forelse($stokMenipis as $item)
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-xs text-slate-800">{{ $item->nama_produk }}</h4>
                        <p class="text-[11px] font-mono text-slate-400">Barcode: {{ $item->barcode }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-lg border border-rose-200">
                            Sisa: {{ $item->stok }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-shield-halved text-3xl text-emerald-500/40 mb-2 block"></i>
                    Semua stok produk aman!
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
