<?php
// Helper sederhana untuk menandai menu aktif
function is_active($page_name) {
    if (strpos($_SERVER['REQUEST_URI'], $page_name) !== false) {
        return 'active';
    }
    return '';
}
?>

<aside class="sidebar">
    <div class="logo">
        <i class="fas fa-bolt" style="color: var(--accent-color);"></i>
        <span>KASIR<span style="color: var(--accent-color);">PRO</span></span>
    </div>

    <!-- Menampilkan User yang Login -->
    <div class="user-profile" style="padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass);">
        <small style="color: var(--text-secondary);">Halo, Admin</small>
        <h4 style="color: white;">Fahim</h4>
    </div>

    <ul class="menu">
        <li>
            <a href="<?= $base_url ?>/modules/dashboard/dashboard.php" class="<?= is_active('dashboard') ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= $base_url ?>/modules/transaksi/transaksi.php" class="<?= is_active('transaksi') ?>">
                <i class="fas fa-cash-register"></i> Transaksi Kasir
            </a>
        </li>
        <li>
            <a href="<?= $base_url ?>/modules/produk/produk.php" class="<?= is_active('produk') ?>">
                <i class="fas fa-box-open"></i> Data Produk
            </a>
        </li>
        <li>
            <a href="<?= $base_url ?>/modules/kategori/kategori.php" class="<?= is_active('kategori') ?>">
                <i class="fas fa-tags"></i> Kategori
            </a>
        </li>
        <li>
            <a href="<?= $base_url ?>/modules/laporan/laporan.php" class="<?= is_active('laporan') ?>">
                <i class="fas fa-chart-pie"></i> Laporan
            </a>
        </li>
        <?php if(true): // Nanti diganti cek role == admin ?>
        <li>
            <a href="<?= $base_url ?>/modules/user/user.php" class="<?= is_active('user') ?>">
                <i class="fas fa-users-cog"></i> Manajemen User
            </a>
        </li>
        <?php endif; ?>
        
        <li style="margin-top: 30px;">
            <a href="#" id="themeToggle" onclick="toggleTheme(event)">
                <i class="fas fa-sun"></i> Mode Terang
            </a>
        </li>
        <li>
            <a href="<?= $base_url ?>/modules/auth/api/logout.php" style="color: var(--danger-color); border: 1px solid rgba(255,77,77,0.2);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</aside>

<!-- Container Konten Utama (Dimulai di sidebar, ditutup di footer) -->
<div class="main-content">
