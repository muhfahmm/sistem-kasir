<?php
include '../../../config/koneksi.php';
require_once '../../../config/auth_check.php'; // Cek Sesi Login Logic

if (isset($_POST['simpan'])) {
    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kat  = $_POST['id_kategori'];
    $beli = $_POST['harga_beli'];
    $jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    mysqli_query($conn, "INSERT INTO produk (kode_produk, nama_produk, id_kategori, harga_beli, harga_jual, stok) VALUES ('$kode', '$nama', '$kat', '$beli', '$jual', '$stok')");

} elseif (isset($_POST['update'])) {
    $id   = $_POST['id_produk'];
    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kat  = $_POST['id_kategori'];
    $beli = $_POST['harga_beli'];
    $jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    mysqli_query($conn, "UPDATE produk SET kode_produk='$kode', nama_produk='$nama', id_kategori='$kat', harga_beli='$beli', harga_jual='$jual', stok='$stok' WHERE id_produk='$id'");

} elseif (isset($_GET['act']) && $_GET['act'] == 'delete') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");
}

header("Location: ../index.php");
?>
