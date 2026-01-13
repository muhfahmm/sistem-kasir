-- =====================================================
-- DATABASE SCHEMA V3.0 - SISTEM KASIR
-- Update: 13 Januari 2026
-- Fitur: Simplified Harga (harga_beli & harga_jual → harga)
-- =====================================================

CREATE DATABASE IF NOT EXISTS db_kasir;
USE db_kasir;

-- Tabel Users (Pengguna) - Support Multiple Roles
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'kasir', -- Support: 'admin', 'kasir', atau 'admin,kasir'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Produk - UPDATED: harga_beli & harga_jual diganti jadi harga saja
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT,
    kode_produk VARCHAR(50) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL, -- Harga jual produk
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
);

-- Tabel Pelanggan (Opsional)
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(20),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Transaksi
CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    no_faktur VARCHAR(50) NOT NULL UNIQUE,
    id_user INT NOT NULL,
    id_pelanggan INT,
    tanggal_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_harga DECIMAL(10, 2) NOT NULL,
    bayar DECIMAL(10, 2) NOT NULL,
    kembalian DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE SET NULL
);

-- Tabel Detail Transaksi
CREATE TABLE detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
);

-- =====================================================
-- DATA DUMMY
-- =====================================================

-- Insert data dummy untuk User 
-- Password default: '123' (hash bcrypt yang valid)
INSERT INTO users (username, password, nama_lengkap, role) VALUES 
('admin', '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC', 'Administrator', 'admin'),
('kasir', '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC', 'Kasir Utama', 'kasir'),
('superuser', '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC', 'Super User', 'admin,kasir');
-- User 'superuser' memiliki kedua role (admin dan kasir)

-- Insert data dummy Kategori
INSERT INTO kategori (nama_kategori) VALUES ('Makanan'), ('Minuman'), ('Snack');

-- Insert data dummy Produk - Hanya harga jual
INSERT INTO produk (id_kategori, kode_produk, nama_produk, harga, stok) VALUES
(1, 'MF001', 'Nasi Goreng Spesial', 15000, 100),
(2, 'DR001', 'Es Teh Manis', 3000, 200),
(3, 'SN001', 'Keripik Singkong', 5000, 50);

-- =====================================================
-- CATATAN PENTING
-- =====================================================
-- File ini adalah versi BARU v3.0 dengan simplified harga
-- Untuk database existing, gunakan file: database_migration_v3_simplified_harga.sql
-- 
-- Perbedaan dengan v2.0:
-- - Kolom 'harga_beli' dan 'harga_jual' di tabel produk → diganti jadi 'harga' saja
-- - Lebih sederhana untuk sistem kasir retail
-- 
-- Cara Install Fresh:
-- 1. Drop database lama (jika ada): DROP DATABASE IF EXISTS db_kasir;
-- 2. Jalankan file ini: mysql -u root -p < database_v3_simplified_harga.sql
-- 
-- Cara Migrate dari v2.0:
-- 1. Gunakan file: database_migration_v3_simplified_harga.sql
-- =====================================================

SELECT 'Database v3.0 created successfully with Simplified Harga!' as status;
