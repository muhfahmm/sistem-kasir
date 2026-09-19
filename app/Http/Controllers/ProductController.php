<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit']);

        if ($request->search) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();
        $units = Unit::all();

        return view('products.index', compact('products', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'barcode' => 'required|string|unique:tb_products,barcode',
            'category_id' => 'required|exists:tb_categories,id',
            'unit_id' => 'required|exists:tb_units,id',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'barcode' => 'required|string|unique:tb_products,barcode,' . $product->id,
            'category_id' => 'required|exists:tb_categories,id',
            'unit_id' => 'required|exists:tb_units,id',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('products.index')->with('error', "Produk '{$product->nama_produk}' tidak dapat dihapus karena sudah terikat dengan riwayat transaksi penjualan.");
        }
    }
}
