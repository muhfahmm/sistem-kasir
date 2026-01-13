<?php
session_start();
require_once '../../../config/koneksi.php';

$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = 'kasir'; // Default register sebagai kasir

// Cek username duplikat
$check = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
if(mysqli_num_rows($check) > 0){
    header("Location: ../register.php?error=Username sudah digunakan!");
    exit;
}

$query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama_lengkap', '$role')";

if(mysqli_query($conn, $query)){
    header("Location: ../login.php?success=Registrasi berhasil, silakan login");
} else {
    header("Location: ../register.php?error=Gagal mendaftar");
}
?>
