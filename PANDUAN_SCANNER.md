# 📷 Panduan Menggunakan Fitur Scanner Barcode/QR Code

## ✅ Fitur Scanner Sudah Aktif!

Scanner barcode/QR code sudah tersedia di halaman **Transaksi/POS**.

---

## 🚀 Cara Menggunakan:

### 1. Akses Halaman Transaksi
```
http://localhost/website sistem kasir/app/modules/transaksi/index.php
```

Atau login sebagai:
- **Kasir** → Otomatis redirect ke transaksi
- **Admin** → Pilih menu Transaksi

---

### 2. Aktifkan Kamera

#### Untuk Admin:
1. Klik tombol **"Scan"** (icon kamera) di pojok kanan atas search bar
2. Browser akan minta izin akses kamera → Klik **"Allow"**
3. Kamera akan aktif dan tampil preview

#### Untuk Kasir (Auto-Start):
1. Kamera **otomatis aktif** saat halaman dibuka
2. Tidak perlu klik tombol Scan
3. Langsung arahkan ke barcode/QR code

---

### 3. Scan Barcode/QR Code

1. **Arahkan kamera** ke barcode/QR code produk
2. **Tunggu deteksi** (biasanya < 1 detik)
3. **Auto-lock** → Spinner loading muncul
4. **Validasi** → Cek apakah kode valid
5. **Hasil**:
   - ✅ **Valid** → Produk masuk keranjang + beep sound
   - ❌ **Invalid** → Modal error muncul

---

## 🎯 Fitur Scanner:

### ✅ Yang Bisa Di-scan:
- **QR Code** (2D)
- **Barcode EAN-13** (retail products)
- **Barcode EAN-8**
- **Code 128**
- **Code 39**
- **UPC-A/UPC-E**

### ✅ Fitur Auto-Lock:
- Scanner pause otomatis saat detect
- Lock indicator (spinner) muncul
- Validasi kode (minimal 3 karakter)
- Prevent double scan

### ✅ Feedback:
- **Beep Success** → Produk berhasil ditambahkan
- **Beep Error** → Scan gagal
- **Modal Error** → Pesan error yang jelas

---

## 🔧 Troubleshooting:

### ❌ Kamera Tidak Muncul

**Penyebab**:
1. Browser tidak support
2. Izin kamera ditolak
3. Tidak menggunakan HTTPS/localhost

**Solusi**:
1. **Gunakan browser modern**: Chrome, Edge, Firefox
2. **Cek izin kamera**:
   - Chrome: Klik icon gembok di address bar → Site settings → Camera → Allow
   - Firefox: Klik icon kamera di address bar → Allow
3. **Pastikan akses dari localhost** (bukan IP address)

---

### ❌ Error: "Gagal membuka kamera"

**Solusi**:
```
1. Refresh halaman (F5)
2. Clear browser cache
3. Restart browser
4. Cek apakah kamera digunakan aplikasi lain
```

---

### ❌ Scanner Tidak Detect Barcode

**Penyebab**:
1. Barcode terlalu kecil/buram
2. Pencahayaan kurang
3. Kamera terlalu jauh

**Solusi**:
1. **Dekatkan kamera** ke barcode (jarak 10-20cm)
2. **Pastikan pencahayaan cukup**
3. **Barcode harus jelas** (tidak buram/rusak)
4. **Posisi horizontal** untuk barcode 1D

---

### ❌ Modal Error: "Kode terlalu pendek"

**Penyebab**: Kode yang di-scan < 3 karakter

**Solusi**: Pastikan scan barcode/QR code yang valid, bukan objek random

---

### ❌ Modal Error: "Produk tidak ditemukan"

**Penyebab**: Produk dengan kode tersebut belum ada di database

**Solusi**:
1. Tambah produk baru dengan kode tersebut
2. Atau scan barcode produk yang sudah terdaftar

---

## 📱 Tips & Tricks:

### ✅ Untuk Hasil Terbaik:
1. **Pencahayaan**: Pastikan ruangan cukup terang
2. **Jarak**: 10-20cm dari barcode
3. **Fokus**: Tunggu kamera fokus dulu
4. **Posisi**: Barcode horizontal untuk 1D barcode
5. **Stabil**: Jangan goyang saat scan

### ✅ Shortcut Keyboard:
- **F2** → Focus ke search box
- **Esc** → Close modal error

### ✅ Mode Kasir vs Admin:

| Fitur | Kasir Mode | Admin Mode |
|-------|------------|------------|
| **Auto-start kamera** | ✅ Ya | ❌ Tidak |
| **Fullscreen** | ✅ Ya | ❌ Tidak |
| **Sidebar** | ❌ Hidden | ✅ Tampil |
| **Tombol Scan** | ✅ Ada | ✅ Ada |

---

## 🎬 Flow Penggunaan:

```
1. Buka halaman transaksi
   ↓
2. Klik "Scan" (atau auto-start untuk kasir)
   ↓
3. Browser minta izin kamera → Allow
   ↓
4. Kamera aktif, preview muncul
   ↓
5. Arahkan ke barcode/QR code
   ↓
6. Scanner detect → Auto-lock (spinner)
   ↓
7. Validasi kode
   ↓
   ├─ Valid → Tambah ke cart → Beep → Resume
   └─ Invalid → Modal error → Resume saat OK
```

---

## 🔒 Keamanan & Privacy:

- ✅ **Kamera hanya aktif di halaman transaksi**
- ✅ **Tidak ada recording/penyimpanan video**
- ✅ **Hanya capture frame untuk detect barcode**
- ✅ **Izin kamera diminta setiap sesi**
- ✅ **HTTPS/Localhost required** (browser security)

---

## 📊 Browser Compatibility:

| Browser | Desktop | Mobile | Support |
|---------|---------|--------|---------|
| **Chrome** | ✅ | ✅ | Full |
| **Edge** | ✅ | ✅ | Full |
| **Firefox** | ✅ | ✅ | Full |
| **Safari** | ⚠️ | ⚠️ | Limited |
| **Opera** | ✅ | ✅ | Full |

**Recommended**: Chrome atau Edge untuk performa terbaik

---

## 🎥 Demo Video (Konsep):

```
1. Login sebagai kasir
2. Halaman transaksi terbuka
3. Kamera auto-start
4. Scan barcode produk
5. Lock indicator muncul
6. Produk masuk keranjang
7. Beep sound
8. Scanner resume
9. Scan produk berikutnya
```

---

## 📞 Support:

Jika masih ada masalah:
1. Cek console browser (F12) untuk error
2. Pastikan library html5-qrcode ter-load
3. Test dengan barcode/QR code yang jelas
4. Coba browser lain

---

**Last Updated**: 13 Januari 2026, 15:50 WIB
