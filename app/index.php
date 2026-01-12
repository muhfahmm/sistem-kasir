<?php
// Entry point sederhana
// Jika belum login ke login.php, jika sudah ke dashboard.
// Untuk saat ini kita redirect langsung ke dashboard dulu untuk preview.

header("Location: modules/dashboard/index.php");
exit;
?>
