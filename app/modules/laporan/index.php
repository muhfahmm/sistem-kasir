<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic
include '../../template/header.php';
include '../../template/sidebar.php';
?>

<div class="glass-panel fade-in">
    <div class="d-flex justify-between align-center" style="margin-bottom: 20px;">
        <h3>Laporan Penjualan</h3>
        <form class="d-flex gap-2">
            <input type="date" name="tgl_awal" class="form-control" value="<?= date('Y-m-01') ?>">
            <input type="date" name="tgl_akhir" class="form-control" value="<?= date('Y-m-d') ?>">
            <button class="btn btn-primary">Filter</button>
        </form>
    </div>

    <table class="table-glass">
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kembali</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 30px;">
                    Belum ada data transaksi.
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../template/footer.php'; ?>
