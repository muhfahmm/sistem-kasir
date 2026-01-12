<?php
include '../../config/koneksi.php';
// Session start wajib ditaruh paling atas nanti saat sudah ada login system
session_start();

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
                <tr>
                    <td>#INV-001</td>
                    <td>10:42</td>
                    <td>Rp 45.000</td>
                    <td><span style="color: var(--success-color);">Lunas</span></td>
                </tr>
                <tr>
                    <td>#INV-002</td>
                    <td>10:45</td>
                    <td>Rp 120.000</td>
                    <td><span style="color: var(--success-color);">Lunas</span></td>
                </tr>
                <tr>
                    <td>#INV-003</td>
                    <td>11:00</td>
                    <td>Rp 32.500</td>
                    <td><span style="color: var(--success-color);">Lunas</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tabel Kanan: Produk Terlaris -->
    <div class="glass-panel" style="flex: 1;">
        <h3 style="margin-bottom: 20px;">Produk Terlaris</h3>
        
        <div class="d-flex align-center justify-between" style="padding: 10px 0; border-bottom: 1px solid var(--border-glass);">
            <div class="d-flex align-center gap-2">
                <div style="width: 40px; height: 40px; background: #333; border-radius: 8px;"></div>
                <div>
                    <h4 style="font-size: 14px;">Es Kopi Susu</h4>
                    <small style="color: var(--text-secondary);">150 Terjual</small>
                </div>
            </div>
            <span style="color: var(--success-color);">#1</span>
        </div>

        <div class="d-flex align-center justify-between" style="padding: 10px 0; border-bottom: 1px solid var(--border-glass);">
            <div class="d-flex align-center gap-2">
                <div style="width: 40px; height: 40px; background: #333; border-radius: 8px;"></div>
                <div>
                    <h4 style="font-size: 14px;">Roti Bakar</h4>
                    <small style="color: var(--text-secondary);">120 Terjual</small>
                </div>
            </div>
            <span style="color: var(--accent-color);">#2</span>
        </div>

    </div>
</div>

<?php include '../../template/footer.php'; ?>
