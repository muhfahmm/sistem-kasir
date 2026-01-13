-- =====================================================
-- DATABASE SCHEMA - SISTEM KASIR
-- Version: 3.0 (Latest)
-- Update: 13 Januari 2026
-- Features: Multiple Roles + Simplified Harga
-- =====================================================

CREATE DATABASE IF NOT EXISTS db_kasir;
USE db_kasir;

-- =====================================================
-- TABEL USERS (PENGGUNA)
-- =====================================================
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'kasir', -- Support: 'admin', 'kasir', atau 'admin,kasir'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABEL KATEGORI
-- =====================================================
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABEL PRODUK
-- =====================================================
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT,
    kode_produk VARCHAR(50) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL, -- Harga jual produk (simplified dari harga_beli & harga_jual)
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
);

-- =====================================================
-- TABEL PELANGGAN (OPSIONAL)
-- =====================================================
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(20),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABEL TRANSAKSI
-- =====================================================
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

-- =====================================================
-- TABEL DETAIL TRANSAKSI
-- =====================================================
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

-- Insert Users
-- Password default: '123' (hash bcrypt yang valid)
INSERT INTO users (username, password, nama_lengkap, role) VALUES 
('admin', '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC', 'Administrator', 'admin'),
('kasir', '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC', 'Kasir Utama', 'kasir'),
('superuser', '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC', 'Super User', 'admin,kasir');
-- User 'superuser' memiliki kedua role (admin dan kasir)

-- Insert Kategori
INSERT INTO kategori (nama_kategori) VALUES 
('Makanan'), 
('Minuman'), 
('Snack');

-- Insert Produk
-- Hanya harga jual (simplified dari harga_beli & harga_jual)
INSERT INTO produk (id_kategori, kode_produk, nama_produk, harga, stok) VALUES
(1, 'MF001', 'Nasi Goreng Spesial', 15000, 100),
(2, 'DR001', 'Es Teh Manis', 3000, 200),
(3, 'SN001', 'Keripik Singkong', 5000, 50),
(1, 'MF002', 'Mie Goreng', 12000, 80),
(2, 'DR002', 'Kopi Hitam', 5000, 150),
(3, 'SN002', 'Kacang Goreng', 3000, 100);

-- =====================================================
-- INFORMASI DATABASE
-- =====================================================
-- Version: 3.0
-- Features:
--   1. Multiple Roles Support (VARCHAR untuk role)
--   2. Simplified Harga (hanya kolom 'harga', tanpa harga_beli & harga_jual)
--   3. Password Hash yang Valid (bcrypt)
--   4. Dummy Data Lengkap
--
-- Default Login:
--   Username: admin    | Password: 123 | Role: admin
--   Username: kasir    | Password: 123 | Role: kasir
--   Username: superuser| Password: 123 | Role: admin,kasir
--
-- Cara Install:
--   1. Via phpMyAdmin: Import file ini
--   2. Via MySQL CLI: mysql -u root -p < database.sql
--
-- Cara Migrate dari versi lama:
--   - Dari v1.0 → v2.0: Gunakan database_migration_multiple_roles.sql
--   - Dari v2.0 → v3.0: Gunakan database_migration_v3_simplified_harga.sql
-- =====================================================

SELECT 'Database v3.0 created successfully!' as status;
SELECT 'Default password for all users: 123' as info;
