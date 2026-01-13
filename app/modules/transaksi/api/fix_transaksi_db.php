<?php
include '../../../config/koneksi.php';

echo "<h3>Memperbaiki Database Transaksi...</h3>";

// Cek Kolom Bayar
$checkBayar = mysqli_query($conn, "SHOW COLUMNS FROM transaksi LIKE 'bayar'");
if (mysqli_num_rows($checkBayar) == 0) {
    if(mysqli_query($conn, "ALTER TABLE transaksi ADD COLUMN bayar DECIMAL(15,2) NOT NULL DEFAULT 0 AFTER total_harga")) {
        echo "✅ Kolom 'bayar' berhasil ditambahkan.<br>";
    } else {
        echo "❌ Gagal tambah kolom 'bayar': " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Info: Kolom 'bayar' sudah ada.<br>";
}

// Cek Kolom Kembalian
$checkKembalian = mysqli_query($conn, "SHOW COLUMNS FROM transaksi LIKE 'kembalian'");
if (mysqli_num_rows($checkKembalian) == 0) {
    if(mysqli_query($conn, "ALTER TABLE transaksi ADD COLUMN kembalian DECIMAL(15,2) NOT NULL DEFAULT 0 AFTER bayar")) {
        echo "✅ Kolom 'kembalian' berhasil ditambahkan.<br>";
    } else {
        echo "❌ Gagal tambah kolom 'kembalian': " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Info: Kolom 'kembalian' sudah ada.<br>";
}

echo "<hr>Selesai. Silakan coba transaksi lagi.";
?>
