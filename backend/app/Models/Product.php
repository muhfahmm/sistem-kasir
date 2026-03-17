<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'sku', 'name', 'description', 
        'price', 'cost_price', 'stock_quantity', 
        'min_stock_level', 'barcode', 'image_url', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
