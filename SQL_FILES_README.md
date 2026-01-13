# 📚 File SQL - Quick Reference

## 3 File SQL yang Tersedia:

### 1️⃣ `database.sql` 
**Original Schema (v1.0)** - Tidak diubah
- Role: `ENUM('admin', 'kasir')`
- Untuk referensi atau install tanpa multiple roles

### 2️⃣ `database_v2_multiple_roles.sql`
**Full Schema v2.0** - Install Fresh
- Role: `VARCHAR(50)` support multiple roles
- Untuk database baru dengan fitur terbaru
- Sudah include dummy user dengan role `'admin,kasir'`

### 3️⃣ `database_migration_multiple_roles.sql`
**Migration Script** - Upgrade Existing
- Untuk upgrade database lama ke v2.0
- Tidak kehilangan data existing

---

## 🚀 Pilih Sesuai Kebutuhan:

| Situasi | File yang Digunakan | Command |
|---------|---------------------|---------|
| **Database Baru** | `database_v2_multiple_roles.sql` | `mysql -u root -p < database_v2_multiple_roles.sql` |
| **Upgrade Existing** | `database_migration_multiple_roles.sql` | `mysql -u root -p db_kasir < database_migration_multiple_roles.sql` |
| **Referensi Original** | `database.sql` | (Hanya untuk referensi) |

---

## 📖 Dokumentasi Lengkap:

Baca file `DATABASE_FILES_GUIDE.md` untuk penjelasan detail.

---

**Prinsip**: File SQL original (`database.sql`) tidak pernah diubah.  
Semua update dibuat di file baru dengan versioning yang jelas.
