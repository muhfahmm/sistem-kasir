<?php
// Entry point sederhana
// Jika belum login ke login.php, jika sudah redirect sesuai role.

session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'kasir') {
        header("Location: modules/transaksi/index.php");
    } else {
        header("Location: modules/dashboard/index.php");
    }
} else {
    // Jika belum login, redirect ke login
    header("Location: modules/auth/login.php");
}
exit;
?>
