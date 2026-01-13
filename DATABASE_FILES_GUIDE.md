# 📁 Struktur File SQL - Sistem Kasir

## File SQL yang Tersedia

### 1. `database.sql` (Original - v1.0)
**Status**: ✅ Original, tidak diubah  
**Deskripsi**: Database schema versi awal dengan ENUM role  
**Kapan digunakan**: 
- Setup awal tanpa fitur multiple roles
- Referensi schema original

**Schema Role**:
```sql
role ENUM('admin', 'kasir') DEFAULT 'kasir'
```

---

### 2. `database_v2_multiple_roles.sql` (New - v2.0)
**Status**: ✅ Versi terbaru dengan Multiple Roles  
**Deskripsi**: Database schema lengkap dengan support multiple roles  
**Kapan digunakan**:
- Install fresh database baru
- Ingin langsung pakai fitur multiple roles

**Schema Role**:
```sql
role VARCHAR(50) NOT NULL DEFAULT 'kasir'
-- Support: 'admin', 'kasir', atau 'admin,kasir'
```

**Dummy Data Tambahan**:
```sql
-- User dengan multiple roles
('superuser', 'password_hash', 'Super User', 'admin,kasir')
```

**Cara Install**:
```bash
# Via Command Line
mysql -u root -p < database_v2_multiple_roles.sql

# Via phpMyAdmin
1. Buka phpMyAdmin
2. Klik "New" untuk buat database baru (atau pilih db_kasir jika sudah ada)
3. Tab SQL
4. Copy-paste isi database_v2_multiple_roles.sql
5. Klik Go
```

---

### 3. `database_migration_multiple_roles.sql` (Migration)
**Status**: ✅ Migration script  
**Deskripsi**: Script untuk upgrade database existing dari v1.0 ke v2.0  
**Kapan digunakan**:
- Sudah punya database lama (v1.0)
- Ingin upgrade ke v2.0 tanpa kehilangan data
- Tidak ingin install ulang database

**Yang Dilakukan**:
```sql
ALTER TABLE users 
MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'kasir';
```

**Cara Migrate**:
```bash
# Via Command Line
mysql -u root -p db_kasir < database_migration_multiple_roles.sql

# Via phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database db_kasir (yang sudah ada)
3. Tab SQL
4. Copy-paste isi database_migration_multiple_roles.sql
5. Klik Go
```

---

## 🎯 Kapan Menggunakan File Mana?

### Scenario 1: Install Fresh (Database Baru)
**Gunakan**: `database_v2_multiple_roles.sql`

```bash
# Langkah-langkah:
1. Pastikan MySQL running
2. Jalankan: mysql -u root -p < database_v2_multiple_roles.sql
3. Database db_kasir akan dibuat dengan schema v2.0
4. Langsung bisa pakai fitur multiple roles
```

### Scenario 2: Upgrade Database Existing
**Gunakan**: `database_migration_multiple_roles.sql`

```bash
# Langkah-langkah:
1. Backup database lama (opsional tapi recommended)
2. Jalankan: mysql -u root -p db_kasir < database_migration_multiple_roles.sql
3. Schema akan di-update ke v2.0
4. Data existing tetap aman
```

### Scenario 3: Referensi Schema Original
**Gunakan**: `database.sql`

```bash
# Hanya untuk referensi atau rollback
# Tidak perlu dijalankan jika sudah pakai v2.0
```

---

## 📊 Perbandingan Versi

| Aspek | v1.0 (database.sql) | v2.0 (database_v2_multiple_roles.sql) |
|-------|---------------------|----------------------------------------|
| **Kolom Role** | `ENUM('admin', 'kasir')` | `VARCHAR(50)` |
| **Single Role** | ✅ Ya | ✅ Ya |
| **Multiple Roles** | ❌ Tidak | ✅ Ya |
| **Format Role** | `'admin'` atau `'kasir'` | `'admin'`, `'kasir'`, atau `'admin,kasir'` |
| **Dummy User** | admin, kasir | admin, kasir, superuser |
| **Backward Compatible** | - | ✅ Ya |

---

## 🔄 Alur Upgrade

```
database.sql (v1.0)
    ↓
    ↓ (jalankan migration)
    ↓
database_migration_multiple_roles.sql
    ↓
    ↓ (schema updated)
    ↓
Database v2.0 (sama dengan database_v2_multiple_roles.sql)
```

---

## ✅ Best Practices

### Untuk Development Baru:
1. ✅ Gunakan `database_v2_multiple_roles.sql`
2. ✅ Langsung dapat fitur terbaru
3. ✅ Tidak perlu migration

### Untuk Production Existing:
1. ✅ Backup database dulu
2. ✅ Gunakan `database_migration_multiple_roles.sql`
3. ✅ Test di staging dulu
4. ✅ Verifikasi data tidak hilang
5. ✅ Baru deploy ke production

### Untuk Rollback:
```sql
-- Jika ingin kembali ke ENUM (HATI-HATI!)
ALTER TABLE users 
MODIFY COLUMN role ENUM('admin', 'kasir') DEFAULT 'kasir';

-- WARNING: User dengan role 'admin,kasir' akan error!
```

---

## 📝 Catatan Penting

⚠️ **File `database.sql` tetap dipertahankan** sebagai referensi schema original dan untuk backward compatibility.

✅ **Semua update baru** akan dibuat di file SQL terpisah dengan naming convention:
- `database_v{version}_{feature_name}.sql` - Full schema
- `database_migration_{feature_name}.sql` - Migration script

✅ **Prinsip**: Never modify original files, always create new versioned files.

---

## 🆘 Troubleshooting

**Q: Saya sudah jalankan database.sql, bagaimana upgrade ke v2.0?**
```bash
# Gunakan migration script
mysql -u root -p db_kasir < database_migration_multiple_roles.sql
```

**Q: Saya mau install fresh, file mana yang dipakai?**
```bash
# Gunakan versi terbaru
mysql -u root -p < database_v2_multiple_roles.sql
```

**Q: Apakah data saya akan hilang saat migration?**
```
Tidak! Migration hanya mengubah tipe kolom, data tetap aman.
Tapi tetap recommended untuk backup dulu.
```

**Q: Bagaimana cara backup database?**
```bash
# Backup sebelum migration
mysqldump -u root -p db_kasir > backup_db_kasir_$(date +%Y%m%d).sql
```

---

**Last Updated**: 13 Januari 2026, 11:46 WIB
