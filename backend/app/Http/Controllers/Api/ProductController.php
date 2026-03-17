<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(\App\Models\Product::with('category')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'min_stock_level' => 'integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
            'image_url' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $product = \App\Models\Product::create($validated);
        return response()->json($product->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = \App\Models\Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'sometimes|required|string|unique:products,sku,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'cost_price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'min_stock_level' => 'integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $id,
            'image_url' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $product->update($validated);
        return response()->json($product->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
