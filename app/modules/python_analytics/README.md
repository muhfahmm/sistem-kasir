# Integrasi Python dengan Sistem Kasir

Modul ini mendemonstrasikan bagaimana Python dapat digunakan sebagai "Backend Processing" untuk analisis data yang lebih kompleks.

## Cara Kerja
1. **Python Script (`analytics.py`)**: 
   - Terhubung langsung ke database `db_kasir`.
   - Mengambil data transaksi.
   - Melakukan kalkulasi (contoh: rata-rata pendapatan, trend mingguan).
   - Menyimpan hasil analisis ke file JSON (`analytics_result.json`).
2. **PHP (`view_analytics.php`)**:
   - Membaca file JSON hasil olahan Python.
   - Menampilkan datanya ke user dalam bentuk Dashboard yang cantik.

## Instalasi Python (Windows)
Karena Anda menggunakan XAMPP di Windows:
1. Download Python dari [python.org](https://www.python.org/downloads/)
2. Saat install, **CENTANG "Add Python to PATH"**.
3. Buka Terminal / CMD, install library yang dibutuhkan:
   ```bash
   pip install -r requirements.txt
   ```

## Menjalankan Analisis
Anda bisa menjalankan script python ini secara manual melalui terminal, atau membuatnya berjalan otomatis via Task Scheduler.
```bash
cd "app/modules/python_analytics"
python analytics.py
```

Setelah dijalankan, file `analytics_result.json` akan muncul/terupdate, dan halaman PHP akan menampilkan data terbaru.
