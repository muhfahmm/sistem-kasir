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
            <a href="produk_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Manual</a>
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
                <th>Harga</th>
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
                <td>Rp <?= number_format($d['harga']) ?></td>
                <td>
                    <?php if($d['stok'] <= 5): ?>
                        <span style="color: var(--danger-color);"><?= $d['stok'] ?></span>
                    <?php else: ?>
                        <span style="color: var(--success-color);"><?= $d['stok'] ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="produk_form.php?id=<?= $d['id_produk'] ?>" class="btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px;"><i class="fas fa-edit" style="color: var(--warning-color);"></i></a>
                    <a href="api/proses.php?act=delete&id=<?= $d['id_produk'] ?>" onclick="return confirm('Hapus?')" class="btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px;"><i class="fas fa-trash" style="color: var(--danger-color);"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal / Script Scanner -->
<!-- Scanner Lock Indicator -->
<div id="scannerLockIndicator" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; background: rgba(0,0,0,0.9); padding: 30px; border-radius: 16px; border: 2px solid var(--accent-color); text-align: center;">
    <div class="spinner" style="width: 60px; height: 60px; border: 4px solid rgba(0,212,255,0.3); border-top: 4px solid var(--accent-color); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 15px;"></div>
    <p style="color: var(--accent-color); font-weight: bold; margin: 0;">Barcode Terdeteksi!</p>
    <small style="color: var(--text-secondary);">Mengalihkan ke form tambah produk...</small>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<!-- Modal / Script Scanner -->
<script>
let html5QrcodeScanner = null;
let isScanning = false;
const beepSound = new Audio('../../assets/audio/beep.mp3');

function openCamera() {
    document.getElementById('scanner-container').style.display = 'block';
    
    // Reset state
    isScanning = false;
    document.getElementById('scannerLockIndicator').style.display = 'none';
    
    // Inisialisasi Scanner
    if (html5QrcodeScanner) {
        // Jika sudah ada instance, restart scanning
        resumeScanner();
    } else {
        html5QrcodeScanner = new Html5Qrcode("reader");
        startScanner();
    }
}

function startScanner() {
    const config = { 
        fps: 20, 
        qrbox: { width: 300, height: 150 },
        experimentalFeatures: { useBarCodeDetectorIfSupported: true }
    };
    
    html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess)
    .catch(err => {
        alert("Gagal membuka kamera: " + err);
    });
}

function resumeScanner() {
    html5QrcodeScanner.resume();
}

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) return; // Prevent double scan
    isScanning = true;
    
    console.log("Scan success:", decodedText);
    
    // 1. Play Beep Sound
    beepSound.play().catch(e => console.log("Audio play failed", e));
    
    // 2. Lock/Pause Camera (Visual Freeze)
    try {
        html5QrcodeScanner.pause(true);
    } catch(e) { console.log('Pause error:', e); }
    
    const readerDiv = document.getElementById('reader');
    if(readerDiv) readerDiv.style.opacity = '0.5';
    
    // 3. Show Loading Indicator
    document.getElementById('scannerLockIndicator').style.display = 'block';
    
    // 4. Redirect after short delay
    setTimeout(() => {
        stopCamera().then(() => {
            window.location.href = "produk_form.php?code=" + encodeURIComponent(decodedText);
        });
    }, 800);
}

function stopCamera() {
    return new Promise((resolve) => {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                document.getElementById('scanner-container').style.display = 'none';
                /* Restore opacity */
                const readerDiv = document.getElementById('reader');
                if(readerDiv) readerDiv.style.opacity = '1';
                html5QrcodeScanner = null; // Destroy instance untuk clean state
                resolve();
            }).catch(err => {
                console.error("Failed to stop scanner", err);
                resolve();
            });
        } else {
            document.getElementById('scanner-container').style.display = 'none';
            resolve();
        }
    });
}
</script>

<?php include '../../template/footer.php'; ?>
