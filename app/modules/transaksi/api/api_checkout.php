<?php
session_start();
include '../../../config/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (empty($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong!']);
    exit;
}

$bayar = isset($_POST['bayar']) ? (int)$_POST['bayar'] : 0;
$total = 0;

// Hitung Total Ulang (Validasi Server Side)
foreach ($_SESSION['cart'] as $item) {
    $total += ($item['harga'] * $item['qty']);
}

// Cek Pembayaran
if ($bayar < $total) {
    echo json_encode(['status' => 'error', 'message' => 'Uang pembayaran kurang! Total: ' . number_format($total)]);
    exit;
}

$kembalian = $bayar - $total;
$id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // Fix: Gunakan user_id sesuai login
if ($id_user == 0) {
    // Fallback darurat: Cek user pertama di DB jika session error (seharusnya tidak terjadi kalau login benar)
    $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_user FROM users LIMIT 1"));
    $id_user = $u ? $u['id_user'] : 1;
}

$no_faktur = 'TRX-' . date('YmdHis'); // Generate faktur
$tanggal = date('Y-m-d H:i:s');

// Mulai Transaksi Database
mysqli_autocommit($conn, FALSE);
$error = false;

// 1. Insert Transaksi
$queryTrans = "INSERT INTO transaksi (no_faktur, id_user, tanggal_transaksi, total_harga, bayar, kembalian) 
               VALUES ('$no_faktur', '$id_user', '$tanggal', '$total', '$bayar', '$kembalian')";

if (!mysqli_query($conn, $queryTrans)) {
    $error = true;
    echo json_encode(['status' => 'error', 'message' => 'Gagal simpan transaksi: ' . mysqli_error($conn)]);
} else {
    $id_transaksi = mysqli_insert_id($conn);

    // 2. Insert Detail & Update Stok
    foreach ($_SESSION['cart'] as $item) {
        $id_produk = $item['id'];
        $qty = $item['qty'];
        $harga = $item['harga'];
        $subtotal = $harga * $qty;

        $queryDetail = "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) 
                        VALUES ('$id_transaksi', '$id_produk', '$qty', '$harga', '$subtotal')";
        
        if (!mysqli_query($conn, $queryDetail)) {
            $error = true;
            break;
        }

        // Update Stok
        $queryStok = "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'";
        if (!mysqli_query($conn, $queryStok)) {
            $error = true;
            break;
        }
    }
}

if ($error) {
    mysqli_rollback($conn);
    // Jika belum output error di atas
    if (!isset($response)) echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan database']);
} else {
    mysqli_commit($conn);
    $_SESSION['cart'] = []; // Kosongkan cart
    echo json_encode([
        'status' => 'success', 
        'message' => 'Transaksi Berhasil', 
        'id_transaksi' => $id_transaksi,
        'kembalian' => $kembalian
    ]);
}
?>
