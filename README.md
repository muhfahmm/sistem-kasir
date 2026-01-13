# 📦 Sistem Kasir Premium - Dokumentasi Lengkap

**Version**: 3.0  
**Last Updated**: 13 Januari 2026  
**Author**: Sistem Kasir Development Team

---

## 📋 Daftar Isi

1. [Instalasi](#instalasi)
2. [Fitur Multiple Roles](#fitur-multiple-roles)
3. [Database Schema](#database-schema)
4. [Scanner Barcode/QR Code](#scanner-barcodeqr-code)
5. [Role Helper Functions](#role-helper-functions)
6. [Troubleshooting](#troubleshooting)
7. [Changelog](#changelog)

---

## 🚀 Instalasi

### Persyaratan Sistem
- PHP 7.4+
- MySQL/MariaDB 5.7+
- Apache/Nginx
- Browser modern (Chrome/Edge/Firefox)

### Langkah Instalasi

#### 1. Clone/Download Project
```bash
git clone [repository-url]
cd sistem-kasir
```

#### 2. Setup Database

**Fresh Install (Recommended):**
```bash
# Via MySQL CLI
mysql -u root -p < database.sql
```

**Via phpMyAdmin:**
1. Buka `http://localhost/phpmyadmin`
2. Create database `db_kasir` (jika belum ada)
3. Pilih database `db_kasir`
4. Tab **SQL**
5. Copy-paste isi file `database.sql`
6. Klik **Go**

**Jika punya database lama**, lihat section [Migration Manual](#migration-manual-jika-perlu) di bawah.

#### 3. Konfigurasi Database
Edit file `app/config/koneksi.php`:
```php
$host = "localhost";
$user = "root";
$pass = ""; // Password MySQL
$db = "db_kasir";
```

#### 4. Akses Aplikasi
```
http://localhost/website sistem kasir/
```

### Struktur Folder Proyek
```
website sistem kasir/
├── app/
│   ├── config/             # Konfigurasi & helpers
│   ├── modules/            # Modul fitur
│   │   ├── auth/           # Login & Register
│   │   ├── dashboard/      # dashboard.php
│   │   ├── kategori/       # kategori.php
│   │   ├── laporan/        # laporan.php
│   │   ├── produk/         # produk.php, produk_form.php
│   │   ├── transaksi/      # transaksi.php
│   │   └── user/           # user.php
│   └── template/           # Header, Sidebar, Footer
├── database.sql            # Schema Database
├── README.md               # Dokumentasi
└── index.php               # Redirect ke app/
```

### Default User Accounts

| Username | Password | Role | Akses |
|----------|----------|------|-------|
| `admin` | `123` | admin | Dashboard, semua menu |
| `kasir` | `123` | kasir | Transaksi/POS saja |
| `superuser` | `123` | admin,kasir | Bisa pilih role saat login |

### Migration Manual (Jika Perlu)

Jika Anda punya database lama dan ingin upgrade manual:

#### Dari v1.0 (ENUM role) → v3.0:
```sql
USE db_kasir;

-- Step 1: Ubah ENUM ke VARCHAR
ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'kasir';

-- Step 2: Tambah kolom harga, copy dari harga_jual
ALTER TABLE produk ADD COLUMN harga DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER nama_produk;
UPDATE produk SET harga = harga_jual;

-- Step 3: Hapus kolom lama
ALTER TABLE produk DROP COLUMN harga_beli;
ALTER TABLE produk DROP COLUMN harga_jual;

-- Step 4: Fix password hash (jika login error)
UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' 
WHERE username IN ('admin', 'kasir', 'superuser');
```

#### Dari v2.0 (VARCHAR role, harga_beli/harga_jual) → v3.0:
```sql
USE db_kasir;

-- Tambah kolom harga, copy dari harga_jual
ALTER TABLE produk ADD COLUMN harga DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER nama_produk;
UPDATE produk SET harga = harga_jual;

-- Hapus kolom lama
ALTER TABLE produk DROP COLUMN harga_beli;
ALTER TABLE produk DROP COLUMN harga_jual;
```

### Generate Password Hash (Jika Perlu)

Jika perlu generate password hash baru:

```php
<?php
$password = '123'; // Ganti dengan password yang diinginkan
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash: " . $hash;
?>
```

Atau gunakan online tool: https://bcrypt-generator.com/

---

## 👥 Fitur Multiple Roles

### Deskripsi
User dapat memiliki lebih dari 1 role (admin, kasir, atau keduanya). Saat login, user memilih role yang ingin digunakan.

### Database Schema
```sql
-- Kolom role menggunakan VARCHAR untuk support multiple roles
role VARCHAR(50) NOT NULL DEFAULT 'kasir'
-- Contoh value: 'admin', 'kasir', atau 'admin,kasir'
```

### Cara Kerja

#### 1. Registrasi
- Form registrasi memiliki **checkbox** untuk pilih role
- User bisa pilih Admin, Kasir, atau keduanya
- Role disimpan sebagai string comma-separated

#### 2. Login
- Form login memiliki **dropdown** untuk pilih role
- Hanya role yang dimiliki user yang bisa dipilih
- Session menyimpan:
  - `$_SESSION['role']` → Role aktif yang dipilih
  - `$_SESSION['all_roles']` → Semua role yang dimiliki

#### 3. Routing
```php
if ($_SESSION['role'] == 'kasir') {
    header("Location: modules/transaksi/index.php");
} else {
    header("Location: modules/dashboard/index.php");
}
```

### Migration dari v1.0 ke v2.0

**File**: `database_migration_multiple_roles.sql`

```sql
-- Ubah kolom role dari ENUM ke VARCHAR
ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'kasir';

-- Tambah user dengan multiple roles
INSERT INTO users (username, password, nama_lengkap, role) VALUES 
('superuser', '$2y$10$...', 'Super User', 'admin,kasir');
```

---

## 🗄️ Database Schema

### Version 3.0 - Simplified Harga

#### Perubahan dari v2.0:
- ❌ Hapus kolom `harga_beli`
- ❌ Hapus kolom `harga_jual`
- ✅ Tambah kolom `harga` (harga jual saja)

#### Tabel Produk
```sql
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
```

### Migration v2.0 → v3.0

**File**: `database_migration_v3_simplified_harga.sql`

```sql
-- Backup harga_jual ke kolom harga baru
ALTER TABLE produk ADD COLUMN harga DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER nama_produk;
UPDATE produk SET harga = harga_jual;

-- Hapus kolom lama
ALTER TABLE produk DROP COLUMN harga_beli;
ALTER TABLE produk DROP COLUMN harga_jual;
```

### File SQL yang Tersedia

| File | Deskripsi |
|------|-----------|
| `database.sql` | **Schema v3.0 (Latest)** - Multiple roles + Simplified harga - Untuk fresh install |

**Untuk migration dari versi lama**, lihat section [Migration Manual](#migration-manual-jika-perlu) di atas.

---

## 📷 Scanner Barcode/QR Code

### Fitur
- ✅ Support QR Code (2D)
- ✅ Support Barcode: EAN-13, EAN-8, Code 128, Code 39, UPC-A/E
- ✅ Auto-lock saat detect
- ✅ Visual feedback (camera redup, spinner)
- ✅ Sound effects (beep success/error)
- ✅ Prevent double scan
- ✅ Auto-resume setelah selesai

### Cara Menggunakan

#### 1. Akses Halaman Transaksi
```
http://localhost/website sistem kasir/app/modules/transaksi/transaksi.php
```

#### 2. Klik Tombol "Scan"
- Browser akan minta izin kamera → Klik **"Allow"**
- Camera preview akan muncul

#### 3. Scan Barcode/QR Code
- Arahkan kamera ke barcode (jarak 10-20cm)
- Tunggu deteksi otomatis (< 1 detik)
- Camera akan **redup** (opacity 50%) saat lock
- **Spinner loading** muncul
- **Beep sound** saat sukses
- Produk otomatis masuk keranjang

### Flow Auto-Lock

```
Scan Detected
    ↓
🔒 SCANNER LOCKED
    ↓
Camera opacity → 50% (visual freeze)
    ↓
Spinner loading muncul
    ↓
Validasi kode
    ↓
    ├─ Valid → Tambah ke cart → Beep → Resume (1 detik)
    └─ Invalid → Modal error → Resume (saat modal ditutup)
```

### Troubleshooting Scanner

#### ❌ Kamera Tidak Muncul
**Solusi:**
1. Gunakan browser Chrome/Edge/Firefox
2. Akses dari `localhost` (bukan IP)
3. Klik "Allow" saat browser minta izin kamera
4. Refresh halaman (Ctrl + F5)

#### ❌ Scanner Tidak Detect
**Solusi:**
1. Dekatkan kamera (10-20cm)
2. Pastikan pencahayaan cukup
3. Barcode harus jelas (tidak buram)
4. Posisi horizontal untuk barcode 1D

#### ❌ Error: "Library not loaded"
**Solusi:**
1. Cek koneksi internet (library dari CDN)
2. Clear browser cache
3. Refresh halaman

---

## 🔧 Role Helper Functions

### Deskripsi
Helper functions untuk mempermudah pengecekan role di seluruh aplikasi.

**File**: `app/config/role_helper.php`

### Functions yang Tersedia

#### 1. `hasRole($role_to_check)`
Cek apakah user memiliki role tertentu.
```php
if (hasRole('admin')) {
    echo "User memiliki role admin";
}
```

#### 2. `isAdmin()`
Cek apakah user memiliki role admin.
```php
if (isAdmin()) {
    // Tampilkan menu admin
}
```

#### 3. `isActiveAdmin()`
Cek apakah user sedang login sebagai admin.
```php
if (isActiveAdmin()) {
    include 'sidebar.php'; // UI decision
}
```

#### 4. `getAllRolesDisplay()`
Get semua role dalam format readable.
```php
echo "Role: " . getAllRolesDisplay(); // "Administrator, Kasir"
```

### Perbedaan Penting

| Function | Cek Apa? | Use Case |
|----------|----------|----------|
| `isAdmin()` | Apakah user **punya** role admin? | Permission check |
| `isActiveAdmin()` | Apakah user **sedang login sebagai** admin? | UI/UX check |

**Contoh:**
```php
// User dengan role 'admin,kasir' login sebagai kasir
isAdmin();        // TRUE (punya role admin)
isActiveAdmin();  // FALSE (sedang login sebagai kasir)
```

### Cara Menggunakan

```php
<?php
require_once '../../config/koneksi.php';
require_once '../../config/auth_check.php';
require_once '../../config/role_helper.php'; // Include helper

// Cek role untuk UI
if (isActiveAdmin()) {
    include 'sidebar.php';
} else {
    // Kasir mode - fullscreen
}

// Cek permission
if (hasRole('admin')) {
    echo '<a href="user/index.php">Kelola User</a>';
}
```

---

## 🐛 Troubleshooting

### Login Tidak Bisa Redirect

**Penyebab:**
1. Path redirect salah
2. Password hash tidak valid
3. Role tidak sesuai

**Solusi:**

#### 1. Fix Password Hash
Jalankan file `fix_password.sql`:
```sql
USE db_kasir;
UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' 
WHERE username IN ('admin', 'kasir', 'superuser');
```

#### 2. Verifikasi Database
```sql
SELECT username, role FROM users;
```

#### 3. Clear Browser Cache
- Ctrl + Shift + Delete
- Clear cookies dan cache
- Refresh halaman

### Error 404 Not Found

**Solusi:**
1. Pastikan akses dari: `http://localhost/website sistem kasir/`
2. Bukan dari: `http://localhost/%20website%20sistem%20kasir/`
3. XAMPP Apache harus running

### Database Connection Failed

**Solusi:**
1. Pastikan MySQL running di XAMPP
2. Cek `app/config/koneksi.php`:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = ""; // Sesuaikan password MySQL
   $db = "db_kasir";
   ```
3. Pastikan database `db_kasir` sudah dibuat

### Scanner Library Not Loaded

**Solusi:**
1. Cek koneksi internet (library dari CDN)
2. Buka Console (F12), cek error
3. Refresh halaman (Ctrl + F5)
4. Clear browser cache

---

## 📝 Changelog

### Version 3.0 - 13 Januari 2026 (Sore)

#### Database Schema v3.0 - Simplified Harga
- ✅ Hapus kolom `harga_beli` dan `harga_jual`
- ✅ Tambah kolom `harga` saja
- ✅ Migration script v2.0 → v3.0

#### Update File PHP
- ✅ `produk/index.php` - Tampilan harga
- ✅ `produk/form.php` - Form input harga
- ✅ `produk/api/proses.php` - Query INSERT/UPDATE
- ✅ `transaksi/index.php` - Display harga
- ✅ `transaksi/api/api_cart.php` - Cart logic

#### Fitur Scanner Auto-Lock
- ✅ Auto-lock saat scan
- ✅ Visual freeze (opacity 50%)
- ✅ Lock indicator (spinner)
- ✅ Validasi barcode/QR
- ✅ Modal error modern
- ✅ Sound effects
- ✅ Prevent double scan
- ✅ Auto-resume

### Version 2.0 - 13 Januari 2026 (Pagi)

#### Fitur Multiple Roles
- ✅ Database schema: ENUM → VARCHAR(50)
- ✅ Registration: Checkbox untuk pilih role
- ✅ Login: Dropdown untuk pilih role
- ✅ Session: `role` dan `all_roles`
- ✅ Routing: Berdasarkan role aktif

#### Role Helper Functions
- ✅ 10 helper functions
- ✅ Support multiple roles
- ✅ Dokumentasi lengkap

#### Bug Fixes
- ✅ Missing `<?php` tag di `app/index.php`
- ✅ Missing `<?php` tag di `transaksi/index.php`
- ✅ Variable `$conn` undefined
- ✅ Variable `$is_admin` undefined
- ✅ Login redirect path salah

---

## 📞 Support & Contact

Jika ada pertanyaan atau masalah:
1. Cek Console browser (F12) untuk error
2. Lihat section Troubleshooting di atas
3. Test dengan file `test_scanner.html` untuk debug scanner

---

## 📄 License

Copyright © 2026 Sistem Kasir Development Team

---

**Happy Coding! 🚀**
