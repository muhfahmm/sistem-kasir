-- =====================================================
-- MIGRATION V2.0 → V3.0 - SISTEM KASIR
-- Update: 13 Januari 2026
-- Fitur: Simplified Harga (harga_beli & harga_jual → harga)
-- =====================================================

USE db_kasir;

-- Backup data harga_jual ke kolom harga baru
ALTER TABLE produk ADD COLUMN harga DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER nama_produk;

-- Copy harga_jual ke harga
UPDATE produk SET harga = harga_jual;

-- Hapus kolom harga_beli dan harga_jual
ALTER TABLE produk DROP COLUMN harga_beli;
ALTER TABLE produk DROP COLUMN harga_jual;

-- Verifikasi
SELECT id_produk, kode_produk, nama_produk, harga, stok 
FROM produk 
LIMIT 5;

SELECT 'Migration v2.0 → v3.0 completed successfully!' as status;
SELECT 'Kolom harga_beli dan harga_jual sudah dihapus, diganti dengan harga' as info;
