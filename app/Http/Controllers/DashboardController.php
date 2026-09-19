<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $totalStok = Product::sum('stok');
        $stokMenipis = Product::whereColumn('stok', '<=', 'stok_minimal')->get();
        $totalTransaksiHariIni = Transaction::whereDate('created_at', now()->today())->count();
        $omsetHariIni = Transaction::whereDate('created_at', now()->today())->sum('total_harga');

        $transaksiTerbaru = Transaction::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'totalTransaksiHariIni',
            'omsetHariIni',
            'transaksiTerbaru'
        ));
    }
}
