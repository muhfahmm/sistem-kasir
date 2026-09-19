@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Riwayat Penjualan</h2>
            <p class="text-xs text-slate-500">Daftar transaksi nota penjualan yang telah diproses kasir.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No. Nota</th>
                        <th class="py-3 px-4">Kasir</th>
                        <th class="py-3 px-4">Total Tagihan</th>
                        <th class="py-3 px-4">Pembayaran</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4">Waktu Transaksi</th>
                        <th class="py-3 px-4 text-center">Cetak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $t->no_nota }}</td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $t->user->name ?? 'Kasir' }}</td>
                        <td class="py-3.5 px-4 font-extrabold text-emerald-600">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-4 text-slate-500">
                            Bayar: Rp {{ number_format($t->bayar, 0, ',', '.') }}<br>
                            <span class="text-[10px] text-slate-400">Kembali: Rp {{ number_format($t->kembali, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 border border-slate-200 text-slate-700">
                                {{ $t->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-400">{{ $t->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('pos.receipt', $t->id) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-all">
                                <i class="fa-solid fa-print"></i> Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">Belum ada riwayat transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
