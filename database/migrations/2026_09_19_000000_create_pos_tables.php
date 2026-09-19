<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('tb_units', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan');
            $table->timestamps();
        });

        Schema::create('tb_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tb_categories')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('tb_units')->cascadeOnDelete();
            $table->string('barcode')->unique();
            $table->string('nama_produk');
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimal')->default(5);
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->index('barcode');
        });

        Schema::create('tb_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota')->unique();
            $table->foreignId('user_id')->constrained('tb_users')->restrictOnDelete();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('bayar', 15, 2)->default(0);
            $table->decimal('kembali', 15, 2)->default(0);
            $table->enum('metode_pembayaran', ['cash', 'qris', 'transfer'])->default('cash');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('no_nota');
        });

        Schema::create('tb_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('tb_transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('tb_products')->restrictOnDelete();
            $table->decimal('harga_satuan', 15, 2);
            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        Schema::create('tb_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko')->default('POS Minimarket Modern');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->text('footer_nota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transaction_details');
        Schema::dropIfExists('tb_transactions');
        Schema::dropIfExists('tb_products');
        Schema::dropIfExists('tb_units');
        Schema::dropIfExists('tb_categories');
        Schema::dropIfExists('tb_settings');
    }
};
