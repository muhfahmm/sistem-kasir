-- =====================================================
-- FIX PASSWORD HASH - Sistem Kasir
-- Update: 13 Januari 2026
-- =====================================================

USE db_kasir;

-- Update password untuk semua user default
-- Password: 123
-- Hash yang benar menggunakan password_hash() PHP

UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' WHERE username = 'admin';
UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' WHERE username = 'kasir';
UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' WHERE username = 'superuser';

-- Verifikasi
SELECT username, role, 
       CASE 
           WHEN password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' THEN 'Password OK'
           ELSE 'Password Berbeda'
       END as password_status
FROM users;

SELECT 'Password updated successfully! Login dengan password: 123' as status;
