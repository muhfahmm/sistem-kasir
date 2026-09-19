-- ===================================================
-- SKEMA DATABASE SQL LENGKAP - SISTEM KASIR (POS) LARAVEL
-- Nama Database : db_sistem_kasir
-- Prefiks Tabel  : tb_
-- Tanpa HPP/Harga Beli (Langsung Harga Jual)
-- ===================================================

CREATE DATABASE IF NOT EXISTS `db_sistem_kasir` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_sistem_kasir`;

DROP TABLE IF EXISTS `tb_transaction_details`;
DROP TABLE IF EXISTS `tb_transactions`;
DROP TABLE IF EXISTS `tb_products`;
DROP TABLE IF EXISTS `tb_units`;
DROP TABLE IF EXISTS `tb_categories`;
DROP TABLE IF EXISTS `tb_settings`;
DROP TABLE IF EXISTS `tb_users`;

-- 1. TABEL PENGGUNA (tb_users)
CREATE TABLE `tb_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL KATEGORI (tb_categories)
CREATE TABLE `tb_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL SATUAN (tb_units)
CREATE TABLE `tb_units` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_satuan` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL PRODUK (tb_products) - TANPA HARGA BELI / HPP
CREATE TABLE `tb_products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `unit_id` BIGINT UNSIGNED NOT NULL,
  `barcode` VARCHAR(100) NOT NULL UNIQUE,
  `nama_produk` VARCHAR(255) NOT NULL,
  `harga_jual` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `stok` INT NOT NULL DEFAULT 0,
  `stok_minimal` INT NOT NULL DEFAULT 5,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_barcode` (`barcode`),
  CONSTRAINT `fk_tb_products_category` FOREIGN KEY (`category_id`) REFERENCES `tb_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tb_products_unit` FOREIGN KEY (`unit_id`) REFERENCES `tb_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL TRANSAKSI (tb_transactions)
CREATE TABLE `tb_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_nota` VARCHAR(100) NOT NULL UNIQUE,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `total_harga` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `bayar` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `kembali` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` ENUM('cash', 'qris', 'transfer') NOT NULL DEFAULT 'cash',
  `catatan` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_no_nota` (`no_nota`),
  CONSTRAINT `fk_tb_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABEL DETAIL TRANSAKSI (tb_transaction_details)
CREATE TABLE `tb_transaction_details` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `harga_satuan` DECIMAL(15,2) NOT NULL,
  `jumlah` INT NOT NULL,
  `subtotal` DECIMAL(15,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_tb_details_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `tb_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tb_details_product` FOREIGN KEY (`product_id`) REFERENCES `tb_products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABEL PENGATURAN TOKO (tb_settings)
CREATE TABLE `tb_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_toko` VARCHAR(255) NOT NULL DEFAULT 'POS Minimarket Modern',
  `alamat` TEXT NULL DEFAULT NULL,
  `telepon` VARCHAR(50) NULL DEFAULT NULL,
  `footer_nota` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- DATA AWAL (KATEGORI, SATUAN, SETTING)
-- ===================================================

INSERT INTO `tb_categories` (`id`, `nama_kategori`, `slug`) VALUES
(1, 'Makanan & Snack', 'makanan-snack'),
(2, 'Minuman', 'minuman'),
(3, 'Sembako', 'sembako');

INSERT INTO `tb_units` (`id`, `nama_satuan`) VALUES
(1, 'Pcs'),
(2, 'Botol'),
(3, 'Kg'),
(4, 'Pack'),
(5, 'Box');

INSERT INTO `tb_settings` (`id`, `nama_toko`, `alamat`, `telepon`, `footer_nota`) VALUES
(1, 'POS Minimarket Modern', 'Jl. Merdeka No. 123, Jakarta Central', '0812-3456-7890', 'Terima kasih telah berbelanja di toko kami!');
