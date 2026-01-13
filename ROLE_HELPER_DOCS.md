# Role Helper Functions - Dokumentasi

## 📚 Deskripsi

File `config/role_helper.php` menyediakan helper functions untuk mempermudah pengecekan role di seluruh aplikasi. Mendukung **multiple roles** (user bisa punya role `'admin'`, `'kasir'`, atau `'admin,kasir'`).

---

## 🎯 Fungsi-Fungsi yang Tersedia

### 1. `hasRole($role_to_check)`
Cek apakah user memiliki role tertentu.

**Parameter**: 
- `$role_to_check` (string) - Role yang ingin dicek ('admin' atau 'kasir')

**Return**: `bool`

**Contoh**:
```php
if (hasRole('admin')) {
    echo "User memiliki role admin";
}

if (hasRole('kasir')) {
    echo "User memiliki role kasir";
}
```

---

### 2. `isAdmin()`
Cek apakah user memiliki role admin (tidak peduli role aktif apa).

**Return**: `bool`

**Contoh**:
```php
if (isAdmin()) {
    // User punya role admin (bisa admin saja atau admin,kasir)
    echo "Tampilkan menu admin";
}
```

---

### 3. `isKasir()`
Cek apakah user memiliki role kasir (tidak peduli role aktif apa).

**Return**: `bool`

**Contoh**:
```php
if (isKasir()) {
    // User punya role kasir (bisa kasir saja atau admin,kasir)
    echo "Akses kasir tersedia";
}
```

---

### 4. `hasBothRoles()`
Cek apakah user memiliki kedua role (admin DAN kasir).

**Return**: `bool`

**Contoh**:
```php
if (hasBothRoles()) {
    echo "User adalah superuser dengan akses penuh";
}
```

---

### 5. `getActiveRole()`
Get role yang sedang aktif (yang dipilih saat login).

**Return**: `string` - 'admin' atau 'kasir'

**Contoh**:
```php
$active_role = getActiveRole();
echo "Anda login sebagai: " . $active_role;
```

---

### 6. `getAllRoles()`
Get semua role yang dimiliki user sebagai array.

**Return**: `array`

**Contoh**:
```php
$roles = getAllRoles();
// Jika user punya 'admin,kasir' → return ['admin', 'kasir']
// Jika user punya 'admin' → return ['admin']

foreach ($roles as $role) {
    echo "Role: " . $role . "<br>";
}
```

---

### 7. `isActiveAdmin()`
Cek apakah user **sedang login sebagai admin** (role aktif = admin).

**Return**: `bool`

**Contoh**:
```php
if (isActiveAdmin()) {
    // User login sebagai admin (tampilkan sidebar admin)
    include 'sidebar.php';
}
```

**Use Case**: Untuk menentukan tampilan UI (sidebar vs fullscreen mode)

---

### 8. `isActiveKasir()`
Cek apakah user **sedang login sebagai kasir** (role aktif = kasir).

**Return**: `bool`

**Contoh**:
```php
if (isActiveKasir()) {
    // User login sebagai kasir (fullscreen mode, auto-start camera)
    echo '<script>setTimeout(openCamera, 1000);</script>';
}
```

**Use Case**: Untuk fitur khusus kasir mode

---

### 9. `getRoleDisplayName($role)`
Get nama display untuk role.

**Parameter**:
- `$role` (string) - 'admin' atau 'kasir'

**Return**: `string` - 'Administrator' atau 'Kasir'

**Contoh**:
```php
echo getRoleDisplayName('admin'); // Output: Administrator
echo getRoleDisplayName('kasir'); // Output: Kasir
```

---

### 10. `getAllRolesDisplay()`
Get semua role dalam format display yang readable.

**Return**: `string` - Contoh: "Administrator, Kasir"

**Contoh**:
```php
echo "Role Anda: " . getAllRolesDisplay();
// Output: "Role Anda: Administrator, Kasir"
```

---

## 🚀 Cara Menggunakan

### 1. Include Helper di File PHP
```php
<?php
require_once '../../config/koneksi.php';
require_once '../../config/auth_check.php';
require_once '../../config/role_helper.php'; // Tambahkan ini
```

### 2. Gunakan Functions
```php
// Cek role untuk menampilkan sidebar
if (isActiveAdmin()) {
    include '../../template/sidebar.php';
} else {
    // Kasir mode - fullscreen
    echo '<style>.main-content { margin-left: 0; }</style>';
}

// Cek permission untuk fitur tertentu
if (hasRole('admin')) {
    echo '<a href="user/index.php">Kelola User</a>';
}

// Auto-start camera untuk kasir
if (isActiveKasir()) {
    echo '<script>setTimeout(openCamera, 1000);</script>';
}
```

---

## 📊 Contoh Use Case

### Use Case 1: Tampilan Sidebar
```php
// File: modules/transaksi/index.php
require_once '../../config/role_helper.php';

if (isActiveAdmin()) {
    // User login sebagai admin → tampilkan sidebar
    include '../../template/sidebar.php';
} else {
    // User login sebagai kasir → fullscreen mode
    echo '<style>.main-content { margin-left: 0; }</style>';
}
```

### Use Case 2: Menu Navigation
```php
// File: template/sidebar.php
require_once '../config/role_helper.php';

if (hasRole('admin')) {
    echo '<li><a href="user/index.php">Kelola User</a></li>';
    echo '<li><a href="laporan/index.php">Laporan</a></li>';
}

if (hasRole('kasir')) {
    echo '<li><a href="transaksi/index.php">Transaksi</a></li>';
}
```

### Use Case 3: Welcome Message
```php
// File: dashboard/index.php
require_once '../../config/role_helper.php';

echo "Selamat datang, " . $_SESSION['nama_lengkap'];
echo " (" . getAllRolesDisplay() . ")";
// Output: "Selamat datang, John Doe (Administrator, Kasir)"
```

### Use Case 4: Conditional Features
```php
// File: produk/index.php
require_once '../../config/role_helper.php';

if (isAdmin()) {
    // Tombol hapus hanya untuk admin
    echo '<button onclick="deleteProduct()">Hapus</button>';
}

// Semua role bisa lihat produk
echo '<div class="product-list">...</div>';
```

---

## ⚠️ Perbedaan Penting

### `isAdmin()` vs `isActiveAdmin()`

| Function | Cek Apa? | Use Case |
|----------|----------|----------|
| `isAdmin()` | Apakah user **punya** role admin? | Permission check (boleh akses fitur atau tidak) |
| `isActiveAdmin()` | Apakah user **sedang login sebagai** admin? | UI/UX check (tampilkan sidebar atau fullscreen) |

**Contoh**:
```php
// User dengan role 'admin,kasir' login sebagai kasir

isAdmin();        // TRUE (punya role admin)
isActiveAdmin();  // FALSE (sedang login sebagai kasir)
isKasir();        // TRUE (punya role kasir)
isActiveKasir();  // TRUE (sedang login sebagai kasir)
```

---

## 🔒 Session Variables yang Digunakan

Helper functions ini menggunakan session variables berikut:

| Variable | Deskripsi | Contoh |
|----------|-----------|--------|
| `$_SESSION['role']` | Role aktif yang dipilih saat login | `'admin'` atau `'kasir'` |
| `$_SESSION['all_roles']` | Semua role yang dimiliki user | `'admin,kasir'` atau `'admin'` atau `'kasir'` |

Session ini di-set saat login di file `modules/auth/api/proses_login.php`.

---

## ✅ Best Practices

1. **Selalu include role_helper.php** setelah auth_check.php
   ```php
   require_once '../../config/auth_check.php';
   require_once '../../config/role_helper.php';
   ```

2. **Gunakan `isActiveAdmin()` untuk UI decisions**
   ```php
   if (isActiveAdmin()) {
       include 'sidebar.php'; // UI decision
   }
   ```

3. **Gunakan `hasRole()` atau `isAdmin()` untuk permission checks**
   ```php
   if (isAdmin()) {
       // Permission check - boleh hapus data
       $can_delete = true;
   }
   ```

4. **Gunakan `getAllRolesDisplay()` untuk user-friendly messages**
   ```php
   echo "Role: " . getAllRolesDisplay(); // "Administrator, Kasir"
   ```

---

## 🐛 Troubleshooting

**Q: Function undefined?**
```
Pastikan sudah include role_helper.php:
require_once '../../config/role_helper.php';
```

**Q: hasRole() selalu return false?**
```
Cek apakah $_SESSION['all_roles'] sudah di-set saat login.
Pastikan sudah jalankan migration database dan login ulang.
```

**Q: isActiveAdmin() tidak sesuai ekspektasi?**
```
Cek role yang dipilih saat login.
isActiveAdmin() cek $_SESSION['role'], bukan $_SESSION['all_roles']
```

---

**Created**: 13 Januari 2026  
**File**: `app/config/role_helper.php`  
**Version**: 1.0.0
