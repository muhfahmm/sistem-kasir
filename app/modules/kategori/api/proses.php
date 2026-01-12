<?php
include '../../../config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = $_POST['nama_kategori'];
    
    if (isset($_POST['add'])) {
        mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id_kategori'];
        mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama_kategori' WHERE id_kategori='$id'");
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id_kategori'];
        mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");
    }
    header("Location: ../index.php"); // Kembali ke index kategori
    exit;
}
?>
