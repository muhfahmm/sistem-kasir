<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $makanan = \App\Models\Category::create(['name' => 'Makanan', 'description' => 'Kategori produk makanan']);
        $minuman = \App\Models\Category::create(['name' => 'Minuman', 'description' => 'Kategori produk minuman']);

        $products = [
            ['name' => 'Espresso', 'price' => 25000, 'category_id' => $minuman->id, 'sku' => 'MNM-001'],
            ['name' => 'Latte', 'price' => 30000, 'category_id' => $minuman->id, 'sku' => 'MNM-002'],
            ['name' => 'Croissant', 'price' => 20000, 'category_id' => $makanan->id, 'sku' => 'MKN-001'],
            ['name' => 'Brownies', 'price' => 15000, 'category_id' => $makanan->id, 'sku' => 'MKN-002'],
            ['name' => 'Cappuccino', 'price' => 28000, 'category_id' => $minuman->id, 'sku' => 'MNM-003'],
            ['name' => 'Americano', 'price' => 27000, 'category_id' => $minuman->id, 'sku' => 'MNM-004'],
            ['name' => 'Muffin', 'price' => 18000, 'category_id' => $makanan->id, 'sku' => 'MKN-003'],
            ['name' => 'Orange Juice', 'price' => 22000, 'category_id' => $minuman->id, 'sku' => 'MNM-005'],
        ];

        foreach ($products as $p) {
            \App\Models\Product::create(array_merge($p, [
                'cost_price' => $p['price'] * 0.7,
                'stock_quantity' => rand(10, 50),
                'barcode' => 'BC-' . $p['sku'],
                'min_stock_level' => 5,
                'is_active' => true
            ]));
        }
    }
}
