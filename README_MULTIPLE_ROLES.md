# 🎯 Fitur Multiple Roles - Quick Start

## ✨ Apa yang Baru?

Sekarang user bisa memiliki **2 role sekaligus** (Admin + Kasir) saat mendaftar!

![Register Form](register_multiple_roles_preview.png)

## 🚀 Cara Menggunakan

### 1️⃣ Jalankan Migration Database

**Pilih salah satu cara:**

**Via phpMyAdmin (Paling Mudah):**
```
1. Buka http://localhost/phpmyadmin
2. Pilih database db_kasir
3. Klik tab SQL
4. Copy-paste isi file: database_migration_multiple_roles.sql
5. Klik Go
```

**Via Command Line:**
```bash
mysql -u root -p db_kasir < database_migration_multiple_roles.sql
```

### 2️⃣ Registrasi User Baru

1. Buka: `http://localhost/website sistem kasir/app/modules/auth/register.php`
2. Isi form:
   - Nama Lengkap
   - Username
   - Password
3. **Pilih Role** (bisa pilih 1 atau 2):
   - ☑️ Admin - Akses penuh ke semua fitur
   - ☑️ Kasir - Akses transaksi penjualan
4. Klik **REGISTER**

### 3️⃣ Login dengan Role Pilihan

1. Buka: `http://localhost/website sistem kasir/app/modules/auth/login.php`
2. Masukkan username dan password
3. **Pilih role** yang ingin digunakan:
   - Jika punya kedua role → bisa pilih Admin atau Kasir
   - Jika hanya 1 role → harus pilih role tersebut
4. Sistem akan redirect:
   - **Admin** → Dashboard (`modules/dashboard/index.php`)
   - **Kasir** → POS/Transaksi (`modules/transaksi/index.php`)

## 📊 Contoh Skenario

### Skenario 1: User dengan 2 Role
```
Username: superuser
Role di database: "admin,kasir"

Login sebagai Admin → Masuk ke Dashboard
Login sebagai Kasir → Masuk ke POS
```

### Skenario 2: User dengan 1 Role
```
Username: kasir1
Role di database: "kasir"

Hanya bisa login sebagai Kasir → Masuk ke POS
```

## 🔧 Perubahan Teknis

| File | Perubahan |
|------|-----------|
| `database.sql` | Kolom `role`: `ENUM` → `VARCHAR(50)` |
| `register.php` | Tambah checkbox untuk pilih role |
| `proses_register.php` | Handle array roles, simpan sebagai CSV |
| `proses_login.php` | Parse multiple roles, validasi role |
| `index.php` | Fixed missing `<?php` tag |

## 📁 File Baru

- ✅ `database_migration_multiple_roles.sql` - Script migration
- ✅ `FITUR_MULTIPLE_ROLES.md` - Dokumentasi lengkap
- ✅ `MIGRATION_GUIDE.md` - Panduan migration step-by-step
- ✅ `README_MULTIPLE_ROLES.md` - Quick start guide (file ini)

## ✅ Testing Checklist

- [ ] Migration database berhasil
- [ ] Registrasi dengan role Admin saja
- [ ] Registrasi dengan role Kasir saja
- [ ] Registrasi dengan kedua role (Admin + Kasir)
- [ ] Login user dengan 1 role
- [ ] Login user dengan 2 role (pilih Admin)
- [ ] Login user dengan 2 role (pilih Kasir)
- [ ] Routing ke halaman yang benar

## 🐛 Troubleshooting

**Q: Error saat migration?**
- Pastikan database `db_kasir` sudah ada
- Cek koneksi MySQL sudah running

**Q: Tidak bisa pilih 2 role saat registrasi?**
- Pastikan sudah jalankan migration database
- Clear browser cache dan refresh halaman

**Q: User lama tidak bisa login?**
- User lama tetap bisa login normal
- Tidak perlu update data existing
- Role 'admin' atau 'kasir' tetap valid

## 📚 Dokumentasi Lengkap

Untuk detail lebih lanjut, baca:
- `FITUR_MULTIPLE_ROLES.md` - Penjelasan lengkap fitur
- `MIGRATION_GUIDE.md` - Panduan migration detail
- `CHANGELOG.md` - History perubahan

---

**Dibuat**: 13 Januari 2026  
**Versi**: 1.0.0  
**Status**: ✅ Ready to Use
