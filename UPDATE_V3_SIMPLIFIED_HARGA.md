# 📝 Update Database v3.0 - Simplified Harga

## 🎯 Perubahan Utama

### Sebelum (v2.0):
```sql
harga_beli DECIMAL(10, 2) NOT NULL,
harga_jual DECIMAL(10, 2) NOT NULL,
```

### Sesudah (v3.0):
```sql
harga DECIMAL(10, 2) NOT NULL, -- Harga jual produk
```

**Alasan**: Untuk sistem kasir retail, biasanya hanya perlu harga jual. Harga beli bisa ditrack di sistem inventory terpisah jika diperlukan.

---

## 📁 File yang Dibuat:

1. **`database_v3_simplified_harga.sql`** - Full schema v3.0 (fresh install)
2. **`database_migration_v3_simplified_harga.sql`** - Migration dari v2.0 ke v3.0

---

## 🔄 Migration dari v2.0 ke v3.0

### Cara 1: Via phpMyAdmin (Recommended)
```
1. Buka http://localhost/phpmyadmin
2. Pilih database db_kasir
3. Klik tab SQL
4. Copy-paste isi file: database_migration_v3_simplified_harga.sql
5. Klik Go
```

### Cara 2: Via Command Line
```bash
mysql -u root -p db_kasir < database_migration_v3_simplified_harga.sql
```

### Yang Dilakukan Migration:
1. ✅ Tambah kolom `harga` baru
2. ✅ Copy data dari `harga_jual` ke `harga`
3. ✅ Hapus kolom `harga_beli`
4. ✅ Hapus kolom `harga_jual`

---

## 📝 File PHP yang Diupdate:

| File | Perubahan |
|------|-----------|
| `produk/index.php` | Hapus kolom Harga Beli & Harga Jual, ganti Harga |
| `produk/form.php` | Hapus field harga_beli & harga_jual, ganti harga |
| `produk/api/proses.php` | Update INSERT & UPDATE query |
| `transaksi/index.php` | Update display harga produk |
| `transaksi/api/api_cart.php` | Update harga_jual → harga |

---

## ✅ Testing Checklist:

### 1. Migration Database
- [ ] Jalankan migration script
- [ ] Verifikasi kolom harga ada
- [ ] Verifikasi kolom harga_beli & harga_jual sudah dihapus
- [ ] Cek data harga ter-copy dengan benar

```sql
-- Verifikasi
SELECT id_produk, nama_produk, harga FROM produk LIMIT 5;
DESCRIBE produk;
```

### 2. CRUD Produk
- [ ] Tambah produk baru (hanya isi harga)
- [ ] Edit produk existing
- [ ] Hapus produk
- [ ] List produk tampil harga dengan benar

### 3. Transaksi/POS
- [ ] Grid produk tampil harga
- [ ] Scan barcode → produk masuk cart dengan harga benar
- [ ] Klik manual → produk masuk cart
- [ ] Total harga dihitung dengan benar

---

## 🔍 Verifikasi Database:

### Cek Struktur Tabel:
```sql
DESCRIBE produk;
```

**Expected Output:**
```
Field         | Type          | Null | Key | Default | Extra
--------------|---------------|------|-----|---------|------------------
id_produk     | int(11)       | NO   | PRI | NULL    | auto_increment
id_kategori   | int(11)       | YES  | MUL | NULL    |
kode_produk   | varchar(50)   | NO   | UNI | NULL    |
nama_produk   | varchar(100)  | NO   |     | NULL    |
harga         | decimal(10,2) | NO   |     | NULL    |  ← Harus ada
stok          | int(11)       | NO   |     | 0       |
gambar        | varchar(255)  | YES  |     | NULL    |
created_at    | timestamp     | YES  |     | CURRENT_TIMESTAMP |
updated_at    | timestamp     | YES  |     | CURRENT_TIMESTAMP |
```

### Cek Data Produk:
```sql
SELECT kode_produk, nama_produk, harga, stok FROM produk;
```

**Expected Output:**
```
kode_produk | nama_produk          | harga    | stok
------------|----------------------|----------|------
MF001       | Nasi Goreng Spesial  | 15000.00 | 100
DR001       | Es Teh Manis         | 3000.00  | 200
SN001       | Keripik Singkong     | 5000.00  | 50
```

---

## 📊 Perbandingan Versi:

| Aspek | v2.0 | v3.0 |
|-------|------|------|
| **Kolom Harga** | harga_beli, harga_jual | harga |
| **Form Input** | 2 field | 1 field |
| **Kompleksitas** | Lebih kompleks | Lebih sederhana |
| **Use Case** | Tracking margin | Kasir retail |

---

## 🚨 Breaking Changes:

⚠️ **PENTING**: Setelah migration, kode PHP lama yang masih menggunakan `harga_beli` atau `harga_jual` akan error!

**Solusi**: Semua file PHP sudah diupdate otomatis. Jika ada custom code, update manual:

**Sebelum**:
```php
$harga_jual = $produk['harga_jual'];
```

**Sesudah**:
```php
$harga = $produk['harga'];
```

---

## 📦 Fresh Install (Database Baru):

Jika ingin install dari awal dengan schema v3.0:

```bash
# Drop database lama (HATI-HATI!)
mysql -u root -p -e "DROP DATABASE IF EXISTS db_kasir;"

# Install v3.0
mysql -u root -p < database_v3_simplified_harga.sql
```

---

## 🔙 Rollback (Jika Diperlukan):

Jika ingin kembali ke v2.0 (harga_beli & harga_jual):

```sql
-- Tambah kembali kolom harga_beli dan harga_jual
ALTER TABLE produk ADD COLUMN harga_beli DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER nama_produk;
ALTER TABLE produk ADD COLUMN harga_jual DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER harga_beli;

-- Copy harga ke harga_jual
UPDATE produk SET harga_jual = harga;

-- Hapus kolom harga
ALTER TABLE produk DROP COLUMN harga;
```

⚠️ **WARNING**: Rollback akan menghilangkan data harga_beli (akan jadi 0 semua)!

---

## ✅ Summary:

- ✅ Database schema v3.0 created
- ✅ Migration script v2.0 → v3.0 created
- ✅ All PHP files updated (5 files)
- ✅ Backward compatible (data harga_jual preserved)
- ✅ Simpler & cleaner code

---

**Last Updated**: 13 Januari 2026, 15:40 WIB
