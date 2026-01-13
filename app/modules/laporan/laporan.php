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
        <tbody>
            <?php
            // Ambil filter tanggal
            $tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
            $tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

            // Query Data Transaksi
            // Gunakan LEFT JOIN ke users untuk ambil nama kasir
            $query = mysqli_query($conn, "
                SELECT t.*, u.nama_lengkap 
                FROM transaksi t 
                LEFT JOIN users u ON t.id_user = u.id_user 
                WHERE t.tanggal_transaksi BETWEEN '$tgl_awal 00:00:00' AND '$tgl_akhir 23:59:59'
                ORDER BY t.tanggal_transaksi DESC
            ");

            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td>
                            <strong style="color: var(--accent-color);"><?= $row['no_faktur'] ?></strong>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal_transaksi'])) ?></td>
                        <td>Rp <?= number_format($row['total_harga']) ?></td>
                        <td>Rp <?= number_format($row['bayar']) ?></td>
                        <td>Rp <?= number_format($row['kembalian']) ?></td>
                        <td><?= $row['nama_lengkap'] ?: 'System' ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 30px;">Data tidak ditemukan pada periode ini.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../../template/footer.php'; ?>
