# RANCANGAN SISTEM WEBSITE KASIR (POINT OF SALE)

Dokumen ini berisi rancangan sistem, alur kerja, dan fitur yang diperlukan untuk membangun aplikasi kasir yang modern, aman, dan mudah digunakan.

## 1. Teknologi yang Digunakan
*   **Backend**: Native PHP (Versi 7.4 atau 8.x)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, CSS3 (Vanilla / Custom), JavaScript (Vanilla / jQuery opsional)
*   **Desain**: Modern UI dengan sentuhan *Glassmorphism* (Transparan, Blur, Elegan).

## 2. Struktur Folder & Arsitektur
Sistem menggunakan pendekatan modular agar mudah dikembangkan dan dirawat.

```
/ (Root)
├── app/                # Folder Utama Source Code
│   ├── assets/         # File statis (CSS, JS, Gambar)
│   ├── config/         # Konfigurasi (Koneksi Database, Helper)
│   ├── modules/        # Logika fitur terpisah per folder
│   │   ├── auth/       # Login/Logout
│   │   ├── dashboard/  # Halaman utama setelah login
│   │   ├── produk/     # CRUD Produk & Stok
│   │   ├── kategori/   # CRUD Kategori
│   │   ├── transaksi/  # Halaman Kasir (Cart/Checkout)
│   │   ├── laporan/    # Laporan Penjualan
│   │   └── user/       # Manajemen Akun Pengguna
│   ├── template/       # Potongan layout (Header, Footer, Sidebar)
│   └── index.php       # Routing utama / Entry point (Opsional)
├── database.sql        # Blueprint database
└── SISTEM_KASIR_PLAN.md # Dokumentasi Perencanaan
```

## 3. Pembagian Hak Akses (Role)
Sistem membedakan akses antara **Admin** dan **Kasir**.

### A. Administrator (Pemilik/Manajer)
*   Mengelola Data Master (Produk, Kategori, Supplier).
*   Mengelola Data Pengguna (Tambah/Hapus Kasir).
*   Melihat Dashboard Statistik (Omset harian, barang terlaris).
*   Melihat semua laporan transaksi.
*   Bisa melakukan segalanya yang bisa dilakukan Kasir.

### B. Kasir (Petugas)
*   **Halaman Transaksi (POS):** Fokus utama untuk input penjualan.
*   Cetak Struk.
*   Melihat riwayat transaksi shift-nya sendiri.
*   Laporan harian pribadi.

## 4. Fitur Utama & Alur Kerja

### 1. Sistem Autentikasi (Keamanan)
*   Login menggunakan Username & Password (Hash Bcrypt).
*   Session Management (Otomatis logout jika tidak aktif).
*   Proteksi halaman (Kasir tidak bisa akses halaman Admin).

### 2. Manajemen Produk (Inventory)
*   Kode Produk (Bisa scan barcode jika ada alat, atau manual).
*   Stok Real-time (Berkurang otomatis saat transaksi).
*   Peringatan Stok Menipis.
*   Upload Gambar Produk agar tampilan kasir menarik.

### 3. Point of Sale (Halaman Kasir)
*   **UI Grid**: Menampilkan gambar produk agar mudah dipilih (Touchscreen friendly).
*   **Search Cepat**: Cari nama/kode produk tanpa loading ulang (AJAX).
*   **Scan Barcode via Kamera**: 
    - Menggunakan kamera HP/Webcam untuk scan barcode produk.
    - **Teknologi**: Menggunakan library JavaScript **`html5-qrcode`** yang berjalan di browser.
    - **Alur Transaksi**: Scan -> Masuk Keranjang.
    - **Alur Input Produk Baru**: Jika admin men-scan kode yang **belum terdaftar**, sistem otomatis membuka formulir tambah produk dengan kode terisi, lalu meminta input Kategori/Label dan Nama Produk.
*   **Keranjang Belanja Sementara**: List barang yang akan dibayar.
*   **Kalkulasi Otomatis**: Subtotal, Diskon (Opsional), Total, Bayar, Kembalian.
*   **Cetak Struk**: Print langsung ke thermal printer atau popup print browser.

### 4. Laporan (Reporting)
*   Laporan Harian, Bulanan, Tahunan.
*   Filter berdasarkan rentang tanggal.
*   Export ke Excel/PDF (Opsional untuk pengembangan lanjut).

## 5. Standar Desain (UI/UX)
Agar aplikasi terasa premium dan tidak "kaku":
*   **Color Palette**: Gunakan warna yang nyaman di mata (jangan terlalu kontras nabrak). Misal: Dark Mode dengan aksen neon soft, atau Light Mode yang bersih.
*   **Interaksi**: Hover effect pada tombol, animasi halus saat modal muncul.
*   **Responsif**: Layout bisa menyesuaikan jika dibuka di Tablet/iPad.

## 6. Daftar Todo (Tahapan Pengerjaan)
1.  [x] Buat Database SQL.
2.  [x] Buat Struktur Folder.
3.  [x] Buat File Koneksi Database (`config/koneksi.php`).
4.  [x] Buat Template Layout (Header/Sidebar/Footer) dengan desain Glassmorphism.
5.  [x] Buat Halaman Login & Register (Auth) dengan role selection.
6.  [x] Buat Modul Dashboard.
7.  [x] Buat Modul Produk (CRUD + **Smart Scan Input**).
8.  [x] Buat Modul Kategori (CRUD + **Quick Add via AJAX**).
9.  [x] Buat Modul Transaksi - **75% Complete**:
    - [x] Logic Keranjang (Add to Cart via AJAX).
    - [x] Integrasi Scanner di Kasir (html5-qrcode).
    - [x] Smart Product Detection (Auto redirect jika produk baru).
    - [x] Dual Mode UI (Admin vs Kasir).
    - [ ] Proses Pembayaran & Cetak Struk - *Next Priority*.
10. [ ] Buat Modul Laporan (Real data dari database).
11. [x] Fitur Dark/Light Mode.
12. [x] Modal Modern untuk Notifikasi.
13. [x] Pemisahan API ke folder `api/` per modul.

## 7. Fitur Tambahan yang Sudah Diimplementasi
- [x] **Smart Scan**: Scan barcode produk baru langsung buka form input.
- [x] **Quick Add Kategori**: Tambah kategori tanpa pindah halaman.
- [x] **Dual Mode Interface**: Tampilan berbeda untuk Admin dan Kasir.
- [x] **Auto-start Camera**: Kamera otomatis nyala untuk role Kasir.
- [x] **Modal Glassmorphism**: Notifikasi error/success yang modern.
- [x] **Session Protection**: Semua halaman dilindungi auth_check.
- [x] **Clean Architecture**: Logic terpisah di folder api/.

## 8. Teknologi & Library
- **Backend**: PHP Native (7.4+)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3 (Vanilla), JavaScript (Vanilla)
- **Barcode Scanner**: html5-qrcode (via CDN)
- **Icons**: Font Awesome 6.4.0
- **Fonts**: Outfit (Google Fonts)
- **Server**: XAMPP/Apache

