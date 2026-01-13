# 🌐 Cara Akses Sistem Kasir

## ✅ URL yang Benar

### Akses Utama (Recommended):
```
http://localhost/website sistem kasir/
```
atau
```
http://localhost/website sistem kasir/app/
```

Kedua URL di atas akan otomatis redirect ke:
- **Login page** (jika belum login)
- **Dashboard** (jika login sebagai admin)
- **Transaksi/POS** (jika login sebagai kasir)

---

## 📍 URL Halaman-Halaman Penting

### Authentication
- **Login**: `http://localhost/website sistem kasir/app/modules/auth/login.php`
- **Register**: `http://localhost/website sistem kasir/app/modules/auth/register.php`
- **Logout**: `http://localhost/website sistem kasir/app/modules/auth/api/logout.php`

### Admin Pages
- **Dashboard**: `http://localhost/website sistem kasir/app/modules/dashboard/index.php`
- **Kelola Produk**: `http://localhost/website sistem kasir/app/modules/produk/index.php`
- **Kelola Kategori**: `http://localhost/website sistem kasir/app/modules/kategori/index.php`
- **Kelola User**: `http://localhost/website sistem kasir/app/modules/user/index.php`
- **Laporan**: `http://localhost/website sistem kasir/app/modules/laporan/index.php`

### Kasir/POS
- **Transaksi**: `http://localhost/website sistem kasir/app/modules/transaksi/index.php`

---

## 🚀 Quick Start

### 1. Pastikan XAMPP Running
- ✅ Apache: Running
- ✅ MySQL: Running

### 2. Setup Database (Pilih salah satu)

**Opsi A: Fresh Install (Database Baru)**
```sql
-- Via phpMyAdmin atau MySQL CLI
-- Jalankan file: database_v2_multiple_roles.sql
```

**Opsi B: Migrate Existing Database**
```sql
-- Via phpMyAdmin atau MySQL CLI
-- Jalankan file: database_migration_multiple_roles.sql
```

### 3. Akses Aplikasi
1. Buka browser
2. Ketik: `http://localhost/website sistem kasir/`
3. Akan redirect ke login page
4. Login dengan:
   - **Username**: `admin` atau `kasir` atau `superuser`
   - **Password**: `123`
   - **Login Sebagai**: Pilih Admin atau Kasir

---

## 🔐 Default User Accounts

| Username | Password | Role | Akses |
|----------|----------|------|-------|
| `admin` | `123` | admin | Dashboard, semua menu admin |
| `kasir` | `123` | kasir | Transaksi/POS saja |
| `superuser` | `123` | admin,kasir | Bisa login sebagai admin atau kasir |

---

## ⚠️ Troubleshooting

### Error: 404 Not Found

**Penyebab**: URL salah atau file tidak ditemukan

**Solusi**:
1. Pastikan mengakses dari URL yang benar:
   ```
   http://localhost/website sistem kasir/
   ```
   BUKAN:
   ```
   http://localhost/%20website%20sistem%20kasir/
   ```

2. Cek apakah Apache sudah running di XAMPP

3. Pastikan folder ada di `C:\xampp\htdocs\website sistem kasir\`

### Error: Database Connection Failed

**Solusi**:
1. Pastikan MySQL running di XAMPP
2. Cek file `app/config/koneksi.php`:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = ""; // Kosongkan jika default XAMPP
   $db = "db_kasir";
   ```
3. Pastikan database `db_kasir` sudah dibuat

### Error: Session/Login Tidak Berfungsi

**Solusi**:
1. Clear browser cache dan cookies
2. Logout dan login ulang
3. Pastikan sudah jalankan migration database jika menggunakan fitur multiple roles

### Error: Undefined Variable $conn atau $is_admin

**Solusi**:
1. Pastikan semua file PHP sudah di-update dengan versi terbaru
2. File `app/modules/transaksi/index.php` harus punya:
   ```php
   <?php
   require_once '../../config/koneksi.php';
   require_once '../../config/auth_check.php';
   require_once '../../config/role_helper.php';
   ```

---

## 📱 Akses dari Device Lain (Optional)

Jika ingin akses dari HP/tablet di jaringan yang sama:

1. Cari IP komputer server:
   ```cmd
   ipconfig
   ```
   Contoh: `192.168.1.100`

2. Akses dari device lain:
   ```
   http://192.168.1.100/website sistem kasir/
   ```

3. Pastikan firewall tidak memblokir port 80

---

## 🎯 Flow Aplikasi

```
http://localhost/website sistem kasir/
    ↓
index.php (root) → redirect ke app/
    ↓
app/index.php → cek session
    ↓
    ├─ Belum login → modules/auth/login.php
    ├─ Login sebagai admin → modules/dashboard/index.php
    └─ Login sebagai kasir → modules/transaksi/index.php
```

---

## 📝 Catatan Penting

1. ✅ **Selalu akses dari root**: `http://localhost/website sistem kasir/`
2. ✅ **Jangan akses langsung ke file PHP** kecuali sudah login
3. ✅ **Gunakan Chrome/Firefox** untuk kompatibilitas terbaik
4. ✅ **HTTPS/Localhost required** untuk fitur barcode scanner (camera access)

---

**Last Updated**: 13 Januari 2026, 15:10 WIB
