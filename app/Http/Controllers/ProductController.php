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

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
