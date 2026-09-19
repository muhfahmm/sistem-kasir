<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:tb_categories,nama_kategori',
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil ditambahkan ke database.');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:tb_categories,nama_kategori',
        ]);

        $category = Category::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan!',
            'data' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:tb_categories,nama_kategori,' . $category->id,
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
        ]);

        return redirect()->route('categories.index')->with('success', 'Nama kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('categories.index')->with('error', "Kategori '{$category->nama_kategori}' tidak dapat dihapus karena masih terikat dengan produk di toko.");
        }
    }
}
