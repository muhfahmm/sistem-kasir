# 🔧 Troubleshooting Login - Sistem Kasir

## ❌ Masalah: Login Tidak Bisa Redirect

### Penyebab yang Sudah Diperbaiki:

1. ✅ **Path redirect salah** di `proses_login.php`
   - Sebelum: `Location: ../../modules/transaksi/index.php` ❌
   - Sesudah: `Location: ../../transaksi/index.php` ✅

2. ✅ **Password hash tidak valid** di database
   - Hash lama: `$2y$10$fW3.qC3.qC3...` (dummy/invalid)
   - Hash baru: `$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC` (valid)

---

## 🚀 Langkah-Langkah Fix:

### Step 1: Update Password di Database

Jalankan file `fix_password.sql` untuk update password hash:

**Via phpMyAdmin:**
1. Buka `http://localhost/phpmyadmin`
2. Pilih database `db_kasir`
3. Klik tab **SQL**
4. Copy-paste isi file `fix_password.sql`
5. Klik **Go**

**Via Command Line:**
```bash
mysql -u root -p db_kasir < fix_password.sql
```

**Atau Manual via SQL:**
```sql
USE db_kasir;

UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' WHERE username = 'admin';
UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' WHERE username = 'kasir';
UPDATE users SET password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' WHERE username = 'superuser';
```

### Step 2: Clear Browser Cache & Session

1. **Clear browser cache**: Ctrl + Shift + Delete
2. **Close semua tab** aplikasi
3. **Buka browser baru** atau incognito mode

### Step 3: Test Login

1. Buka: `http://localhost/website sistem kasir/`
2. Login dengan:
   - **Username**: `admin` (atau `kasir` atau `superuser`)
   - **Password**: `123`
   - **Login Sebagai**: Pilih Admin atau Kasir
3. Klik **LOGIN**
4. Seharusnya redirect ke:
   - **Admin** → Dashboard
   - **Kasir** → Transaksi/POS

---

## 🔍 Verifikasi Database

Cek apakah password sudah ter-update dengan benar:

```sql
SELECT username, role, 
       LEFT(password, 20) as password_preview,
       CASE 
           WHEN password = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC' THEN '✅ Valid'
           ELSE '❌ Invalid'
       END as password_status
FROM users;
```

**Expected Output:**
```
username   | role        | password_preview      | password_status
-----------|-------------|-----------------------|----------------
admin      | admin       | $2y$10$uaWN9HczMifs | ✅ Valid
kasir      | kasir       | $2y$10$uaWN9HczMifs | ✅ Valid
superuser  | admin,kasir | $2y$10$uaWN9HczMifs | ✅ Valid
```

---

## 🐛 Jika Masih Tidak Bisa Login:

### Debug 1: Cek Error Login

Jika redirect ke `login.php?error=1`, berarti:
- ❌ Username salah
- ❌ Password salah
- ❌ Password hash tidak cocok

**Solusi:**
1. Pastikan username benar: `admin`, `kasir`, atau `superuser`
2. Pastikan password: `123`
3. Jalankan `fix_password.sql` untuk update hash

### Debug 2: Cek Error Role

Jika redirect ke `login.php?error=Role tidak sesuai dengan akun Anda`, berarti:
- ❌ Role yang dipilih tidak sesuai dengan role user di database

**Solusi:**
1. Cek role user di database:
   ```sql
   SELECT username, role FROM users WHERE username = 'admin';
   ```
2. Pastikan pilih role yang sesuai saat login
3. Jika user punya role `admin,kasir`, bisa pilih salah satu

### Debug 3: Cek Session

Tambahkan debug di `proses_login.php` setelah line 31:

```php
// Debug - hapus setelah selesai
echo "Session set successfully!<br>";
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Username: " . $_SESSION['username'] . "<br>";
echo "Role: " . $_SESSION['role'] . "<br>";
echo "All Roles: " . $_SESSION['all_roles'] . "<br>";
echo "Redirecting to: ";
if ($selected_role == 'kasir') {
    echo "../../transaksi/index.php";
} else {
    echo "../../dashboard/index.php";
}
exit; // Stop di sini untuk debug
```

### Debug 4: Cek Path Redirect

Pastikan file tujuan ada:
```
app/modules/transaksi/index.php  ✅
app/modules/dashboard/index.php  ✅
```

Dari `app/modules/auth/api/proses_login.php`:
- `../../transaksi/index.php` = `app/modules/transaksi/index.php` ✅
- `../../dashboard/index.php` = `app/modules/dashboard/index.php` ✅

---

## 📝 Checklist Troubleshooting

- [ ] XAMPP Apache & MySQL running
- [ ] Database `db_kasir` sudah dibuat
- [ ] Password hash sudah di-update (jalankan `fix_password.sql`)
- [ ] Browser cache sudah di-clear
- [ ] Session cookies sudah di-clear
- [ ] Username benar: `admin`, `kasir`, atau `superuser`
- [ ] Password benar: `123`
- [ ] Role yang dipilih sesuai dengan role user
- [ ] File `proses_login.php` sudah ter-update (path redirect benar)
- [ ] File `transaksi/index.php` dan `dashboard/index.php` ada

---

## 🎯 Test Flow Lengkap

### Test 1: Login sebagai Admin
```
1. Buka: http://localhost/website sistem kasir/
2. Username: admin
3. Password: 123
4. Login Sebagai: Admin
5. Klik LOGIN
6. Expected: Redirect ke Dashboard
```

### Test 2: Login sebagai Kasir
```
1. Buka: http://localhost/website sistem kasir/
2. Username: kasir
3. Password: 123
4. Login Sebagai: Kasir
5. Klik LOGIN
6. Expected: Redirect ke Transaksi/POS
```

### Test 3: Login sebagai Superuser (Admin)
```
1. Buka: http://localhost/website sistem kasir/
2. Username: superuser
3. Password: 123
4. Login Sebagai: Admin
5. Klik LOGIN
6. Expected: Redirect ke Dashboard
```

### Test 4: Login sebagai Superuser (Kasir)
```
1. Buka: http://localhost/website sistem kasir/
2. Username: superuser
3. Password: 123
4. Login Sebagai: Kasir
5. Klik LOGIN
6. Expected: Redirect ke Transaksi/POS
```

---

## 📞 Jika Masih Bermasalah

Cek file log error Apache:
```
C:\xampp\apache\logs\error.log
```

Atau aktifkan error reporting di PHP:
```php
// Tambahkan di awal proses_login.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

**Last Updated**: 13 Januari 2026, 15:12 WIB
