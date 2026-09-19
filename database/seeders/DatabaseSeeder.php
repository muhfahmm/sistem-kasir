<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin & Kasir Users
        User::create([
            'name' => 'Administrator Toko',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        // Kategori Master (Siap Pakai)
        Category::create(['nama_kategori' => 'Makanan & Snack', 'slug' => 'makanan-snack']);
        Category::create(['nama_kategori' => 'Minuman', 'slug' => 'minuman']);
        Category::create(['nama_kategori' => 'Sembako', 'slug' => 'sembako']);

        // Satuan Master (Siap Pakai)
        Unit::create(['nama_satuan' => 'Pcs']);
        Unit::create(['nama_satuan' => 'Botol']);
        Unit::create(['nama_satuan' => 'Kg']);
        Unit::create(['nama_satuan' => 'Pack']);
        Unit::create(['nama_satuan' => 'Box']);

        // Setting Toko
        Setting::create([
            'nama_toko' => 'POS Minimarket Modern',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Central',
            'telepon' => '0812-3456-7890',
            'footer_nota' => 'Terima kasih telah berbelanja di toko kami!',
        ]);
    }
}
