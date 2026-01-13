# Cara Menjalankan Migration Multiple Roles

## Langkah-langkah:

### Opsi 1: Melalui phpMyAdmin (Recommended)
1. Buka browser dan akses `http://localhost/phpmyadmin`
2. Login dengan username dan password MySQL Anda
3. Pilih database `db_kasir` di sidebar kiri
4. Klik tab **SQL** di menu atas
5. Buka file `database_migration_multiple_roles.sql` dengan text editor
6. Copy semua isi file tersebut
7. Paste ke textarea SQL di phpMyAdmin
8. Klik tombol **Go** atau **Kirim** di kanan bawah
9. Tunggu sampai muncul pesan sukses

### Opsi 2: Melalui Command Line
```bash
# Masuk ke direktori project
cd c:\xampp\htdocs\website sistem kasir

# Jalankan migration (ganti 'root' dengan username MySQL Anda jika berbeda)
mysql -u root -p db_kasir < database_migration_multiple_roles.sql

# Masukkan password MySQL ketika diminta
# Jika berhasil, akan muncul pesan "Migration completed successfully!"
```

### Opsi 3: Melalui MySQL Workbench
1. Buka MySQL Workbench
2. Connect ke server MySQL Anda
3. Pilih database `db_kasir`
4. Klik menu **File** → **Open SQL Script**
5. Pilih file `database_migration_multiple_roles.sql`
6. Klik icon **Execute** (petir) atau tekan Ctrl+Shift+Enter
7. Periksa output di bagian bawah untuk memastikan sukses

## Verifikasi Migration Berhasil

Jalankan query berikut untuk memastikan kolom `role` sudah berubah:

```sql
DESCRIBE users;
```

Hasilnya harus menunjukkan:
- Field: `role`
- Type: `varchar(50)` (bukan lagi `enum('admin','kasir')`)
- Default: `kasir`

## Testing Setelah Migration

1. **Buat user baru dengan multiple roles:**
   - Buka `http://localhost/website sistem kasir/app/modules/auth/register.php`
   - Isi form registrasi
   - Centang kedua checkbox (Admin dan Kasir)
   - Klik REGISTER

2. **Login dengan user yang baru dibuat:**
   - Pilih "Admin" di dropdown → Harus masuk ke Dashboard
   - Logout
   - Login lagi, pilih "Kasir" → Harus masuk ke Transaksi/POS

3. **Cek database:**
   ```sql
   SELECT username, role FROM users;
   ```
   User baru harus memiliki role: `admin,kasir`

## Troubleshooting

### Error: "Unknown column 'role' in 'field list'"
- Database belum di-migrate
- Jalankan ulang migration script

### Error: "Data truncated for column 'role'"
- Ini normal jika ada data lama dengan ENUM
- Migration akan otomatis convert ke VARCHAR

### User lama tidak bisa login
- User lama dengan role 'admin' atau 'kasir' tetap bisa login normal
- Tidak perlu update data existing
- Hanya user baru yang bisa punya multiple roles

## Rollback (Jika Diperlukan)

Jika ingin kembali ke ENUM:

```sql
ALTER TABLE users 
MODIFY COLUMN role ENUM('admin', 'kasir') DEFAULT 'kasir';
```

⚠️ **Warning**: Rollback akan menghapus data user yang memiliki multiple roles (role='admin,kasir')!
