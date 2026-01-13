<?php
require_once '../../../config/koneksi.php';
require_once '../../../config/auth_check.php';

// Ambil ID Transaksi dari URL
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id_transaksi)) {
    die("ID Transaksi tidak ditemukan.");
}

// Ambil Data Transaksi
$queryOrder = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'");
$transaksi = mysqli_fetch_assoc($queryOrder);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Ambil Detail Item
$queryDetail = mysqli_query($conn, "SELECT dt.*, p.nama_produk 
                                    FROM detail_transaksi dt 
                                    JOIN produk p ON dt.id_produk = p.id_produk 
                                    WHERE dt.id_transaksi = '$id_transaksi'");

// Format Tanggal
$tanggal = date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #<?= $transaksi['no_faktur'] ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font struk */
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 58mm; /* Lebar kertas thermal standar 58mm */
            background: #fff;
            color: #000;
        }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px; }
        .store-name { font-size: 16px; font-weight: bold; }
        .address { font-size: 10px; }
        
        .meta { margin-bottom: 10px; font-size: 10px; }
        
        .items { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .items th { text-align: left; border-bottom: 1px dashed #000; }
        .items td { padding: 2px 0; vertical-align: top; }
        .qty { width: 15%; text-align: center; }
        .item-name { width: 55%; }
        .price { width: 30%; text-align: right; }
        
        .totals { width: 100%; font-weight: bold; border-top: 1px dashed #000; padding-top: 5px; }
        .totals td { text-align: right; padding: 2px 0; }
        
        .footer { text-align: center; margin-top: 15px; font-size: 10px; border-top: 1px dashed #000; padding-top: 5px; }
        
        /* Hide everything else when printing */
        @media print {
            @page { margin: 0; }
            body { margin: 0; padding: 5px; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <div class="store-name">KASIR PRO</div>
        <div class="address">Jl. Contoh No. 123, Kota Demo</div>
    </div>

    <div class="meta">
        No: <?= $transaksi['no_faktur'] ?><br>
        Tgl: <?= $tanggal ?><br>
        Kasir: <?= $_SESSION['nama_lengkap'] ?>
    </div>

    <table class="items">
        <?php while($d = mysqli_fetch_assoc($queryDetail)): ?>
        <tr>
            <td colspan="3" class="item-name"><?= $d['nama_produk'] ?></td>
        </tr>
        <tr>
            <td class="qty"><?= $d['jumlah'] ?>x</td>
            <td style="text-align: right;"><?= number_format($d['harga_satuan']) ?></td>
            <td class="price"><?= number_format($d['subtotal']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <table class="totals">
        <tr>
            <td>Total:</td>
            <td>Rp <?= number_format($transaksi['total_harga']) ?></td>
        </tr>
        <tr>
            <td>Bayar:</td>
            <td>Rp <?= number_format($transaksi['bayar']) ?></td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td>Rp <?= number_format($transaksi['kembalian']) ?></td>
        </tr>
    </table>

    <div class="footer">
        Terima Kasih Atas Kunjungan Anda<br>
        Barang yang sudah dibeli tidak dapat ditukar/dikembalikan
    </div>

</body>
</html>
