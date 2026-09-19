<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit'])->where('stok', '>', 0)->get();
        $categories = Category::all();
        $units = Unit::all();
        return view('pos.index', compact('products', 'categories', 'units'));
    }

    public function getProductByBarcode($barcode)
    {
        $product = Product::with(['category', 'unit'])->where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan!'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $product]);
    }

    public function quickStoreProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'barcode' => 'required|string|unique:tb_products,barcode',
            'category_id' => 'required|exists:tb_categories,id',
            'unit_id' => 'required|exists:tb_units,id',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:1',
            'stok_minimal' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $product = Product::create([
            'nama_produk' => $request->nama_produk,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'stok_minimal' => $request->stok_minimal ?? 5,
        ]);

        $product->load(['category', 'unit']);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan!',
            'data' => $product
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,qris,transfer'
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $itemsToProcess = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stok < $item['qty']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok produk {$product->nama_produk} tidak mencukupi (sisa: {$product->stok})"
                    ], 400);
                }

                $subtotal = $product->harga_jual * $item['qty'];
                $totalHarga += $subtotal;

                $itemsToProcess[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'harga_satuan' => $product->harga_jual,
                    'subtotal' => $subtotal
                ];
            }

            if ($request->bayar < $totalHarga) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nominal pembayaran kurang dari total harga!'
                ], 400);
            }

            $kembali = $request->bayar - $totalHarga;

            // Simpan Transaksi Header
            $transaction = Transaction::create([
                'no_nota' => 'INV-' . date('YmdHis') . '-' . rand(100, 999),
                'user_id' => auth()->id() ?? 1,
                'total_harga' => $totalHarga,
                'bayar' => $request->bayar,
                'kembali' => $kembali,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan ?? null
            ]);

            // Simpan Detail & Update Stok
            foreach ($itemsToProcess as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'harga_satuan' => $item['harga_satuan'],
                    'jumlah' => $item['qty'],
                    'subtotal' => $item['subtotal']
                ]);

                // Pengurangan Stok Produk
                $item['product']->decrement('stok', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil diproses!',
                'transaction_id' => $transaction->id,
                'no_nota' => $transaction->no_nota
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);
        $setting = Setting::first();
        return view('pos.receipt', compact('transaction', 'setting'));
    }
}
