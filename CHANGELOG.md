# CHANGELOG - Sistem Kasir Premium

Dokumen ini mencatat semua perubahan dan update yang telah dilakukan pada project ini.

---

## 🎯 Session 1 - 12 Januari 2026

### ✅ Database & Struktur Awal
- [x] Membuat database schema (`database.sql`) dengan tabel: users, kategori, produk, pelanggan, transaksi, detail_transaksi
- [x] Membuat struktur folder modular di dalam folder `app/`
- [x] Setup folder: `assets/`, `config/`, `modules/`, `template/`
- [x] Memindahkan semua source code ke dalam folder `app/` (kecuali database.sql dan dokumentasi)

### ✅ Konfigurasi & Template
- [x] Membuat file koneksi database (`config/koneksi.php`)
- [x] Membuat template layout dengan desain **Glassmorphism**:
  - `template/header.php` - Meta tags, CSS, Fonts
  - `template/sidebar.php` - Navigation menu dengan role detection
  - `template/footer.php` - Scripts & closing tags
- [x] Membuat `assets/css/style.css` dengan CSS Variables untuk theming

### ✅ Sistem Autentikasi
- [x] Halaman Login (`modules/auth/login.php`)
  - Dropdown pemilihan role (Admin/Kasir)
  - Validasi role sesuai database
  - Redirect berbeda per role (Admin → Dashboard, Kasir → Transaksi)
- [x] Halaman Register (`modules/auth/register.php`)
  - Default role: Kasir
  - Validasi username duplikat
- [x] Logout functionality (`modules/auth/api/logout.php`)
- [x] Session protection (`config/auth_check.php`)
- [x] **Modal Modern** untuk notifikasi error/success (menggantikan alert biasa)

### ✅ Modul Dashboard
- [x] Halaman dashboard (`modules/dashboard/index.php`)
- [x] Statistik cards: Omset, Total Transaksi, Total Produk
- [x] Tabel transaksi terakhir (dummy data)
- [x] List produk terlaris (dummy data)

### ✅ Modul Kategori
- [x] CRUD Kategori (`modules/kategori/index.php`)
- [x] Inline form untuk tambah kategori
- [x] API endpoint untuk AJAX add kategori (`modules/kategori/api/api_ajax_add.php`)
- [x] Hapus kategori dengan konfirmasi

### ✅ Modul Produk
- [x] List produk dengan indikator stok (`modules/produk/index.php`)
- [x] Form tambah/edit produk (`modules/produk/form.php`)
- [x] **Smart Scan Input**: Scan barcode → Auto-fill kode produk
- [x] **Quick Add Kategori**: Tombol (+) di form untuk tambah kategori tanpa pindah halaman
- [x] Auto-fill kode produk dari parameter URL (`?code=xxx`)
- [x] Upload gambar produk (placeholder)
- [x] API proses CRUD (`modules/produk/api/proses.php`)

### ✅ Modul Transaksi (Point of Sale)
- [x] Halaman kasir dengan layout 2 kolom (`modules/transaksi/index.php`)
  - Kiri: Grid produk & Scanner
  - Kanan: Keranjang belanja & Total
- [x] **Dual Mode UI**:
  - Admin Mode: Sidebar lengkap, kamera manual
  - Kasir Mode: Full screen, kamera auto-start, header minimalis
- [x] Integrasi **html5-qrcode** library untuk scan barcode
- [x] Optimasi scanner: FPS 20, qrbox 300x150, native detector
- [x] **Smart Product Detection**:
  - Jika produk ditemukan → Masuk keranjang + beep sound
  - Jika produk tidak ditemukan → Tawarkan tambah produk baru
- [x] API Cart (`modules/transaksi/api/api_cart.php`):
  - Add by ID (klik manual)
  - Add by barcode (scan)
  - View cart (HTML render)
  - Get total (JSON)
  - Remove item
  - Reset cart
- [x] Real-time cart update (AJAX)
- [x] Shortcut keyboard: F2 untuk search

### ✅ Modul User & Laporan (Skeleton)
- [x] Halaman list user (`modules/user/index.php`)
- [x] Halaman laporan dengan filter tanggal (`modules/laporan/index.php`)

### ✅ Fitur UI/UX
- [x] **Dark/Light Mode Toggle**:
  - Tombol di sidebar
  - Persistent via localStorage
  - Smooth transition
  - Custom color palette per theme
- [x] **Glassmorphism Design**:
  - Transparent panels dengan blur
  - Gradient borders
  - Soft shadows
  - Premium aesthetics
- [x] **Form Styling**:
  - Select dropdown dengan styling custom
  - Dark mode: Background #1e1e1e
  - Light mode: Background #ffffff
  - Force override dengan !important
- [x] **Responsive Layout**
- [x] **Animasi**:
  - Fade in untuk page load
  - Slide up untuk modal
  - Hover effects pada buttons

### ✅ Arsitektur & Best Practices
- [x] Pemisahan logic ke folder `api/` di setiap modul
- [x] Struktur MVC-like (View terpisah dari Logic)
- [x] Session management yang aman
- [x] SQL Injection protection (mysqli_real_escape_string)
- [x] Password hashing (bcrypt)
- [x] Clean URL setelah modal ditutup

---

## 📋 Fitur yang Sudah Berfungsi
1. ✅ Login/Register dengan role selection
2. ✅ Dashboard statistik (dummy data)
3. ✅ CRUD Kategori
4. ✅ CRUD Produk + Smart Scan
5. ✅ Scan Barcode untuk input produk baru
6. ✅ Kasir POS dengan keranjang real-time
7. ✅ Scan Barcode untuk transaksi
8. ✅ Dark/Light mode
9. ✅ Modal modern untuk notifikasi

## 🚧 Fitur yang Belum Selesai
- [ ] Proses Pembayaran & Cetak Struk
- [ ] Laporan Penjualan (Real data)
- [ ] Dashboard dengan data real dari database
- [ ] Manajemen User (CRUD)
- [ ] Export laporan (Excel/PDF)
- [ ] Sound effects (beep saat scan)
- [ ] Keyboard shortcuts lengkap
- [ ] Hold/Pending transaksi
- [ ] Kirim struk via WhatsApp

---

## 🐛 Bug Fixes
- Fixed: Dropdown select tetap putih di dark mode → Solved dengan `!important`
- Fixed: Redirect error 404 pada register → Path `../register.php`
- Fixed: Scanner tidak langsung terbaca → FPS dinaikkan ke 20
- Fixed: CSS syntax error pada table-glass selector

---

## 📝 Catatan Teknis
- Library: html5-qrcode (via CDN)
- Font: Outfit (Google Fonts)
- Icons: Font Awesome 6.4.0
- Database: MySQL/MariaDB
- Server: XAMPP (PHP 7.4+)

---

**Last Updated**: 12 Januari 2026, 22:21 WIB
