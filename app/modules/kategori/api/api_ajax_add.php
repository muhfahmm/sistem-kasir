<?php
include '../../../config/koneksi.php';
session_start();
// Security check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$nama = isset($_POST['nama_kategori']) ? mysqli_real_escape_string($conn, $_POST['nama_kategori']) : '';

if (!empty($nama)) {
    $insert = mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    if ($insert) {
        $id_baru = mysqli_insert_id($conn);
        echo json_encode(['status' => 'success', 'id' => $id_baru, 'nama' => $nama]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal simpan database']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nama kategori kosong']);
}
?>
