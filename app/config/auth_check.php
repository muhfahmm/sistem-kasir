<?php
// File helper untuk pengecekan sesi login secara global
session_start();

// Mencegah caching halaman (Prevent Back Button after Logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: ../../modules/auth/login.php");
    exit;
}
?>
