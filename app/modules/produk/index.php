<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic
include '../../template/header.php';
include '../../template/sidebar.php';
?>

<div class="glass-panel fade-in">
    <div class="d-flex justify-between align-center" style="margin-bottom: 20px;">
        <h3>Data Produk</h3>
        <div class="d-flex gap-2">
            <button class="btn" onclick="openCamera()" style="background: var(--accent-color); color: white;"><i class="fas fa-qrcode"></i> Scan Barang Baru</button>
            <a href="form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Manual</a>
        </div>
    </div>

    <!-- Scanner Container (Hidden by default) -->
    <div id="scanner-container" class="glass-panel" style="display:none; margin-bottom: 20px; position: relative;">
        <h4 style="margin-bottom: 10px; text-align:center;">Arahkan Kamera ke Barcode</h4>
        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
        <button class="btn btn-danger" onclick="stopCamera()" style="position: absolute; top: 10px; right: 10px;">Tutup</button>
    </div>

    <table class="table-glass">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT produk.*, kategori.nama_kategori 
                      FROM produk 
                      LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori 
                      ORDER BY id_produk DESC";
            $data = mysqli_query($conn, $query);
            while($d = mysqli_fetch_assoc($data)):
            ?>
            <tr>
                <td><span style="background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;"><?= $d['kode_produk'] ?></span></td>
                <td><?= $d['nama_produk'] ?></td>
                <td><?= $d['nama_kategori'] ?></td>
                <td>Rp <?= number_format($d['harga_beli']) ?></td>
                <td>Rp <?= number_format($d['harga_jual']) ?></td>
                <td>
                    <?php if($d['stok'] <= 5): ?>
                        <span style="color: var(--danger-color);"><?= $d['stok'] ?></span>
                    <?php else: ?>
                        <span style="color: var(--success-color);"><?= $d['stok'] ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="form.php?id=<?= $d['id_produk'] ?>" class="btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px;"><i class="fas fa-edit" style="color: var(--warning-color);"></i></a>
                    <a href="api/proses.php?act=delete&id=<?= $d['id_produk'] ?>" onclick="return confirm('Hapus?')" class="btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px;"><i class="fas fa-trash" style="color: var(--danger-color);"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal / Script Scanner -->
<script>
let html5QrcodeScanner = null;

function openCamera() {
    document.getElementById('scanner-container').style.display = 'block';
    
    // Inisialisasi Scanner
    html5QrcodeScanner = new Html5Qrcode("reader");
    
    // Config: FPS tinggi & Box lebar untuk barcode
    const config = { 
        fps: 20, 
        qrbox: { width: 300, height: 150 },
        experimentalFeatures: { useBarCodeDetectorIfSupported: true }
    };
    
    html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess);
}

function onScanSuccess(decodedText, decodedResult) {
    // Stop scanning
    stopCamera();
    
    // Redirect ke form tambah produk dengan kode terisi otomatis
    // System akan menganggap ini produk baru yang ingin diberi label/kategori
    window.location.href = "form.php?code=" + encodeURIComponent(decodedText);
}

function stopCamera() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            document.getElementById('scanner-container').style.display = 'none';
        }).catch(err => {
            console.error("Failed to stop scanner", err);
        });
    } else {
        document.getElementById('scanner-container').style.display = 'none';
    }
}
</script>

<?php include '../../template/footer.php'; ?>
