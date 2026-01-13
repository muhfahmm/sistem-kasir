-- Migration untuk mendukung multiple roles
-- Ubah kolom role dari ENUM menjadi VARCHAR untuk support multiple roles

USE db_kasir;

-- Backup data existing (opsional, untuk safety)
-- CREATE TABLE users_backup AS SELECT * FROM users;

-- Ubah tipe kolom role dari ENUM ke VARCHAR
ALTER TABLE users 
MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'kasir';

-- Update existing data (jika ada)
-- Data yang sudah ada akan tetap valid karena 'admin' dan 'kasir' masih valid
-- Untuk user yang ingin punya 2 role, bisa diupdate manual atau lewat registrasi baru

-- Contoh update manual jika ingin memberikan kedua role ke user tertentu:
-- UPDATE users SET role = 'admin,kasir' WHERE username = 'admin';

SELECT 'Migration completed successfully!' as status;
