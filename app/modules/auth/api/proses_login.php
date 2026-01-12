<?php
session_start();
require_once '../../../config/koneksi.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    // Verifikasi Password (Default database.sql pake hash password_hash)
    if (password_verify($password, $row['password'])) {
        // Set Session
        $_SESSION['user_id'] = $row['id_user'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['nama_lengkap'] = $row['nama_lengkap'];

        header("Location: ../../dashboard/index.php");
        exit;
    }
}

header("Location: ../login.php?error=1");
exit;
?>
