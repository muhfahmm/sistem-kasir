<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic

include '../../template/header.php';
include '../../template/sidebar.php';
?>

<!-- Header Halaman -->
<div class="d-flex justify-between align-center fade-in" style="margin-bottom: 30px;">
    <div>
        <h2 style="font-weight: 600;">Dashboard</h2>
        <p style="color: var(--text-secondary);">Ringkasan performa toko Anda hari ini.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn glass-panel"><i class="fas fa-calendar"></i> 12 Januari 2026</button>
        <button class="btn btn-primary"><i class="fas fa-plus"></i> Transaksi Baru</button>
    </div>
</div>

<!-- Statistik Cards -->
<div class="d-flex gap-2 fade-in" style="flex-wrap: wrap; margin-bottom: 30px;">
    <!-- Card Omset -->
    <div class="glass-panel" style="flex: 1; min-width: 250px; background: linear-gradient(135deg, rgba(0,255,153,0.1), rgba(0,0,0,0)); border: 1px solid rgba(0,255,153,0.2);">
        <div class="d-flex justify-between align-center">
            <div>
                <p style="color: var(--text-secondary); margin-bottom: 5px;">Omset Hari Ini</p>
                <h2 style="color: var(--success-color);">Rp 2.500.000</h2>
            </div>
            <div style="background: rgba(0,255,153,0.2); padding: 15px; border-radius: 12px;">
                <i class="fas fa-coins fa-2x" style="color: var(--success-color);"></i>
            </div>
        </div>
    </div>

    <!-- Card Transaksi -->
    <div class="glass-panel" style="flex: 1; min-width: 250px; background: linear-gradient(135deg, rgba(0,212,255,0.1), rgba(0,0,0,0)); border: 1px solid rgba(0,212,255,0.2);">
        <div class="d-flex justify-between align-center">
            <div>
                <p style="color: var(--text-secondary); margin-bottom: 5px;">Total Transaksi</p>
                <h2 style="color: var(--accent-color);">48</h2>
            </div>
             <div style="background: rgba(0,212,255,0.2); padding: 15px; border-radius: 12px;">
                <i class="fas fa-shopping-cart fa-2x" style="color: var(--accent-color);"></i>
            </div>
        </div>
    </div>

    <!-- Card Produk -->
    <div class="glass-panel" style="flex: 1; min-width: 250px; background: linear-gradient(135deg, rgba(255,204,0,0.1), rgba(0,0,0,0)); border: 1px solid rgba(255,204,0,0.2);">
        <div class="d-flex justify-between align-center">
            <div>
                <p style="color: var(--text-secondary); margin-bottom: 5px;">Total Produk</p>
                <h2 style="color: var(--warning-color);">120 <small style="font-size: 14px; color: #fff;">Item</small></h2>
            </div>
             <div style="background: rgba(255,204,0,0.2); padding: 15px; border-radius: 12px;">
                <i class="fas fa-box fa-2x" style="color: var(--warning-color);"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions & Top Products -->
<div class="d-flex gap-2 fade-in">
    <!-- Tabel Kiri: Transaksi Terakhir -->
    <div class="glass-panel" style="flex: 2;">
        <div class="d-flex justify-between align-center" style="margin-bottom: 20px;">
            <h3>Transaksi Terakhir</h3>
            <a href="#" style="color: var(--accent-color); text-decoration: none;">Lihat Semua</a>
        </div>
        <table class="table-glass">
            <thead>
                <tr>
                    <th>Faktur</th>
                    <th>Waktu</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q_trx = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY tanggal_transaksi DESC LIMIT 5");
                if(mysqli_num_rows($q_trx) > 0) {
                    while($row = mysqli_fetch_assoc($q_trx)):
                ?>
                <tr>
                    <td><?= $row['no_faktur'] ?></td>
                    <td><?= date('H:i', strtotime($row['tanggal_transaksi'])) ?></td>
                    <td>Rp <?= number_format($row['total_harga']) ?></td>
                    <td><span style="color: var(--success-color);">Lunas</span></td>
                </tr>
                <?php endwhile; 
                } else {
                    echo '<tr><td colspan="4" style="text-align: center;">Belum ada data</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Tabel Kanan: Produk Terlaris -->
    <div class="glass-panel" style="flex: 1;">
        <h3 style="margin-bottom: 20px;">Produk Terlaris</h3>
        
        <div id="top-products-list">
            <?php
            $q_top = mysqli_query($conn, "
                SELECT p.nama_produk, p.gambar, SUM(dt.jumlah) as terjual 
                FROM detail_transaksi dt 
                JOIN produk p ON dt.id_produk = p.id_produk 
                GROUP BY dt.id_produk 
                ORDER BY terjual DESC 
                LIMIT 5
            ");
            
            if(mysqli_num_rows($q_top) > 0):
                $rank = 1;
                while($item = mysqli_fetch_assoc($q_top)):
                    $rankColor = ($rank == 1) ? 'var(--success-color)' : (($rank == 2) ? 'var(--accent-color)' : 'var(--warning-color)');
            ?>
            <div class="d-flex align-center justify-between" style="padding: 10px 0; border-bottom: 1px solid var(--border-glass);">
                <div class="d-flex align-center gap-2">
                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        <?php if(!empty($item['gambar']) && file_exists('../../assets/img/produk/'.$item['gambar'])): ?>
                            <img src="../../assets/img/produk/<?= $item['gambar'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-box" style="color: var(--text-secondary);"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 style="font-size: 14px; margin: 0;"><?= $item['nama_produk'] ?></h4>
                        <small style="color: var(--text-secondary);"><?= $item['terjual'] ?> Terjual</small>
                    </div>
                </div>
                <span style="color: <?= $rankColor ?>; font-weight: bold;">#<?= $rank++ ?></span>
            </div>
            <?php endwhile; else: ?>
                <p style="color: var(--text-secondary); text-align: center; margin-top: 20px;">Belum ada penjualan</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include '../../template/footer.php'; ?>
