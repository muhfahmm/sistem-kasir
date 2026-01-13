# Fitur Multiple Roles - Sistem Kasir

## Deskripsi
Fitur ini memungkinkan user untuk memiliki lebih dari satu role (Admin dan Kasir) secara bersamaan saat mendaftar.

## Perubahan yang Dilakukan

### 1. Database Schema
- **Kolom `role` di tabel `users`** diubah dari `ENUM('admin', 'kasir')` menjadi `VARCHAR(50)`
- Format penyimpanan:
  - Single role: `'admin'` atau `'kasir'`
  - Multiple roles: `'admin,kasir'` (comma-separated)

### 2. Form Registrasi (`register.php`)
- Ditambahkan checkbox untuk memilih role:
  - ☐ Admin - Akses penuh ke semua fitur
  - ☐ Kasir - Akses transaksi penjualan
- User bisa memilih salah satu atau kedua role
- Validasi JavaScript memastikan minimal 1 role dipilih
- Hover effect pada checkbox untuk UX yang lebih baik

### 3. Proses Registrasi (`proses_register.php`)
- Menerima array roles dari checkbox
- Validasi hanya menerima 'admin' dan 'kasir'
- Menyimpan roles sebagai comma-separated string
- Error handling untuk role tidak valid atau tidak dipilih

### 4. Proses Login (`proses_login.php`)
- Memparse role dari database (split by comma)
- Validasi role yang dipilih di form login terhadap roles yang dimiliki user
- Menyimpan 2 session variables:
  - `$_SESSION['role']` - Role yang dipilih saat login
  - `$_SESSION['all_roles']` - Semua role yang dimiliki user

### 5. Routing (`index.php`)
- Tetap menggunakan `$_SESSION['role']` untuk routing
- User dengan multiple roles akan diarahkan sesuai role yang dipilih saat login

## Cara Menggunakan

### Untuk User Baru (Registrasi)
1. Buka halaman registrasi
2. Isi nama lengkap, username, dan password
3. Pilih role yang diinginkan (bisa pilih keduanya):
   - Centang "Admin" untuk akses admin
   - Centang "Kasir" untuk akses kasir
   - Atau centang keduanya untuk akses penuh
4. Klik tombol REGISTER

### Untuk Login
1. Masukkan username dan password
2. Pilih role yang ingin digunakan untuk sesi ini:
   - Jika user punya role "admin,kasir", bisa pilih login sebagai Admin atau Kasir
   - Jika user hanya punya 1 role, harus pilih role tersebut
3. Sistem akan redirect sesuai role yang dipilih:
   - Kasir → `modules/transaksi/index.php` (POS)
   - Admin → `modules/dashboard/index.php` (Dashboard)

## Migration Database

Jalankan file `database_migration_multiple_roles.sql` untuk mengupdate database yang sudah ada:

```bash
# Melalui phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database db_kasir
3. Klik tab SQL
4. Copy-paste isi file database_migration_multiple_roles.sql
5. Klik Go

# Atau melalui command line
mysql -u root -p db_kasir < database_migration_multiple_roles.sql
```

## Contoh Data

### User dengan Single Role
```sql
INSERT INTO users (username, password, nama_lengkap, role) 
VALUES ('kasir1', '$2y$10$...', 'Kasir Satu', 'kasir');
```

### User dengan Multiple Roles
```sql
INSERT INTO users (username, password, nama_lengkap, role) 
VALUES ('superuser', '$2y$10$...', 'Super User', 'admin,kasir');
```

## Keamanan
- Validasi role dilakukan di server-side (PHP)
- Hanya role 'admin' dan 'kasir' yang diperbolehkan
- Session menyimpan role aktif untuk authorization
- Password tetap di-hash menggunakan `password_hash()`

## Backward Compatibility
✅ User dengan single role ('admin' atau 'kasir') tetap berfungsi normal
✅ Tidak perlu update data existing, cukup ubah schema
✅ Login flow tetap sama, hanya validasi yang lebih fleksibel

## Testing Checklist
- [ ] Registrasi dengan role admin saja
- [ ] Registrasi dengan role kasir saja
- [ ] Registrasi dengan kedua role (admin,kasir)
- [ ] Login user dengan single role
- [ ] Login user dengan multiple roles (pilih admin)
- [ ] Login user dengan multiple roles (pilih kasir)
- [ ] Validasi error jika tidak pilih role saat registrasi
- [ ] Validasi error jika pilih role yang tidak dimiliki saat login
- [ ] Routing ke halaman yang benar sesuai role
