<?php
include '../../config/koneksi.php';
include '../../template/header.php';
include '../../template/sidebar.php';

// Coba baca file JSON hasil dari Python
$json_file = 'analytics_result.json';
$analytics_data = null;
$last_update = "Belum pernah dijalankan";

if (file_exists($json_file)) {
    $json_content = file_get_contents($json_file);
    $analytics_data = json_decode($json_content, true);
    $last_update = $analytics_data['generated_at'];
}
?>

<div class="glass-panel fade-in">
    <div class="d-flex justify-between align-center mb-4" style="margin-bottom: 2rem;">
        <div>
            <h1><i class="fab fa-python" style="color: #3776AB;"></i> Python Analytics Dashboard</h1>
            <p style="color: var(--text-secondary);">Integrasi Data Science dengan PHP</p>
        </div>
        <div class="text-right">
            <small>Terakhir diupdate: <?= $last_update ?></small><br>
            <span class="badge" style="background: rgba(55, 118, 171, 0.2); color: #3776AB; padding: 5px 10px; border-radius: 20px; border: 1px solid #3776AB;">
                Powered by Python
            </span>
        </div>
    </div>

    <?php if (!$analytics_data): ?>
        <div class="text-center" style="padding: 50px;">
            <i class="fas fa-robot fa-3x" style="color: var(--text-secondary); margin-bottom: 20px;"></i>
            <h3>Data Analisis Belum Tersedia</h3>
            <p>Silahkan jalankan script Python terlebih dahulu untuk men-generate data.</p>
            <code style="background: rgba(0,0,0,0.3); padding: 10px; display: block; margin: 20px auto; max-width: 500px; border-radius: 8px;">
                python app/modules/python_analytics/analytics.py
            </code>
            <p>Atau baca <a href="README.md" target="_blank" style="color: var(--accent-color);">Petunjuk Instalasi</a></p>
        </div>
    <?php else: ?>

        <!-- Summary Cards -->
        <div class="d-flex gap-2" style="margin-bottom: 30px;">
            <div class="glass-panel w-100" style="background: linear-gradient(135deg, rgba(55, 118, 171, 0.1), rgba(255, 212, 59, 0.1)); border-color: #3776AB;">
                <h4 style="color: #3776AB;">Rata-rata Harian (7 Hari)</h4>
                <h2 style="font-size: 2rem;">Rp <?= number_format($analytics_data['average_daily_revenue'], 0, ',', '.') ?></h2>
            </div>
            <div class="glass-panel w-100">
                <h4>Total Pendapatan (7 Hari)</h4>
                <h2 style="font-size: 2rem;">Rp <?= number_format($analytics_data['total_revenue_period'], 0, ',', '.') ?></h2>
            </div>
        </div>

        <!-- Data Table -->
        <h3>Tren Penjualan</h3>
        <table class="table-glass">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analytics_data['sales_trend'] as $row): ?>
                <tr>
                    <td><?= date('d F Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= $row['total_transaksi'] ?> Transaksi</td>
                    <td style="color: var(--success-color);">+ Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>

<?php include '../../template/footer.php'; ?>
