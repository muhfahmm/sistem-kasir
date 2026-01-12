<?php
// Konfigurasi Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kasir";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone agar waktu transaksi sesuai (WIB)
date_default_timezone_set('Asia/Jakarta');

// Base URL (Ganti sesuai nama folder htdocs anda)
// Pastikan tidak ada slash di akhir
$base_url = "http://localhost/website%20sistem%20kasir/app";
?>
