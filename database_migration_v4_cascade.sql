-- =====================================================
-- DATABASE MIGRATION - v4.0
-- Feature: Enable Cascading Delete for Products
-- Description: Allows deleting products even if they have transaction history (history will be deleted too)
-- =====================================================

USE db_kasir;

-- 1. Drop existing foreign key
-- Note: We assume the standard constraint name 'detail_transaksi_ibfk_2'
-- If this fails, check your specific constraint name using 'SHOW CREATE TABLE detail_transaksi'
ALTER TABLE detail_transaksi DROP FOREIGN KEY detail_transaksi_ibfk_2;

-- 2. Add new foreign key with ON DELETE CASCADE
ALTER TABLE detail_transaksi 
ADD CONSTRAINT detail_transaksi_ibfk_2 
FOREIGN KEY (id_produk) REFERENCES produk(id_produk) 
ON DELETE CASCADE;

SELECT 'Migration v4.0 (Cascading Delete) applied successfully!' as status;
