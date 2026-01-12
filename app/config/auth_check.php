<?php
// File helper untuk pengecekan sesi login secara global
session_start();

if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    // Kita gunakan path relative standar asumsi file ini dipanggil dari modules/..
    // Atau gunakan base_url dari koneksi.php jika sudah di-include sebelumnya
    
    // Fallback simple redirect
    header("Location: ../../modules/auth/login.php");
    exit;
}
?>
