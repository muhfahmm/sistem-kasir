<?php
require_once '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic
require_once '../../config/role_helper.php'; // Helper functions untuk role

// Cek apakah user adalah admin (role aktif = admin)
$is_admin = isActiveAdmin();

include '../../template/header.php';

// LOGIC LAYOUT & SIDEBAR
if ($is_admin) {
    // Jika Admin, include sidebar standar (yang membuka .main-content)
    include '../../template/sidebar.php';
} else {
    // Jika kasir, kita buka .main-content manual dengan style full width
    echo '<style>
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 25px !important; }
        /* Hide hamburger menu on admin mode if exists */
        .hamburger-menu { display: none !important; }
    </style>';
    echo '<div class="main-content">';
}
?>

<!-- Header Navigasi (Untuk Kasir) -->
<div class="glass-header d-flex justify-between align-center custom-pos-header" style="padding: 15px 25px; margin-bottom: 25px; border-radius: 16px;">
    <div class="d-flex align-center gap-2">
        <div style="width: 40px; height: 40px; background: rgba(0, 212, 255, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-bolt" style="color: var(--accent-color); font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 style="margin:0; font-size: 1.2rem; letter-spacing: 0.5px;">KASIR MODE</h3>
            <small style="color: var(--text-secondary); font-size: 0.8rem;">Sistem Transaksi Cepat</small>
        </div>
    </div>
    <div class="d-flex align-center gap-3">
        <div style="text-align: right;">
            <span style="display: block; font-weight: bold; font-size: 0.9rem;">Hi, <?= $_SESSION['nama_lengkap'] ?></span>
            <small style="color: var(--success-color);"><i class="fas fa-circle" style="font-size: 8px;"></i> Online</small>
        </div>
        <div style="height: 30px; width: 1px; background: rgba(255,255,255,0.1);"></div>
        
        <a href="../../modules/auth/api/logout.php" class="btn btn-danger btn-sm" style="padding: 8px 15px; border-radius: 10px;" onclick="return confirm('Keluar dari aplikasi?');"><i class="fas fa-power-off"></i> Logout</a>
    </div>
</div>

<!-- Layout Utama 2 Kolom -->
<div class="d-flex" style="height: calc(100vh - 140px); gap: 20px;">
    
    <!-- KOLOM KIRI: SCANNER & PRODUK (Flex Grow) -->
    <div style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
        
        <!-- Area Scanner (Top Center) -->
        <div class="glass-panel" style="margin-bottom: 20px; padding: 20px; text-align: center; position: relative; overflow: hidden; flex-shrink: 0;">
            <div class="d-flex justify-center align-center gap-3" style="margin-bottom: 0;">
                <div style="position: relative; flex: 1; max-width: 400px;">
                    <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                    <input type="text" id="searchProduct" class="form-control" placeholder="Cari Kode / Nama Produk (F2)..." style="width: 100%; padding-left: 40px; height: 45px; font-size: 1rem; border-radius: 12px;">
                </div>
                <button class="btn btn-primary" onclick="openCamera()" style="height: 45px; padding: 0 25px; border-radius: 12px; font-weight: 600; letter-spacing: 0.5px; white-space: nowrap;">
                    <i class="fas fa-camera" style="margin-right: 8px;"></i> Buka Scanner
                </button>
            </div>
            
            <!-- Camera Preview Container (Hidden by default) -->
            <div id="camera-preview" style="display: none; margin-top: 20px; animation: slideDown 0.3s ease;">
                <div style="position: relative; display: inline-block; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 2px solid var(--accent-color);">
                    <div id="reader" style="width: 500px; height: 375px; background: #000;"></div>
                    <button class="btn btn-danger btn-sm" onclick="stopCamera()" style="position: absolute; top: 10px; right: 10px; z-index: 10; padding: 8px; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i>
                    </button>
                    <!-- Overlay Frame -->
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; border: 40px solid rgba(0,0,0,0.5);"></div>
                </div>
                <small style="color: var(--text-secondary); display: block; margin-top: 15px; font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Arahkan kamera ke barcode produk</small>
            </div>
        </div>

        <!-- Grid Produk -->
        <h4 style="margin: 0 0 15px 5px; color: var(--text-secondary); display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-th-large"></i> Daftar Produk
            <span style="font-size: 0.8rem; background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 10px; color: var(--text-secondary);">Klik produk untuk menambah manual</span>
        </h4>
        
        <div style="flex: 1; overflow-y: auto; padding-right: 5px; padding-bottom: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px;">
                <?php
                $produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC");
                while($p = mysqli_fetch_assoc($produk)):
                ?>
                <div class="glass-panel product-card" onclick="addToCart(<?= $p['id_produk'] ?>)">
                    <div class="product-icon">
                        <?php if(!empty($p['gambar']) && file_exists('../../assets/img/produk/'.$p['gambar'])): ?>
                            <img src="../../assets/img/produk/<?= $p['gambar'] ?>" alt="<?= $p['nama_produk'] ?>">
                        <?php else: ?>
                            <i class="fas fa-box"></i>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h5><?= $p['nama_produk'] ?></h5>
                        <p class="price">Rp <?= number_format($p['harga']) ?></p>
                        <div class="d-flex justify-between align-center mt-1">
                            <small class="stock" style="color: var(--text-secondary); font-size: 0.7rem;">Stok: <?= $p['stok'] ?></small>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: KERANJANG (Fixed Width) -->
    <div style="width: 380px; display: flex; flex-direction: column; flex-shrink: 0;">
        <div class="glass-panel" style="flex: 1; display: flex; flex-direction: column; background: rgba(20, 20, 20, 0.95); border: 1px solid var(--accent-color); box-shadow: 0 0 30px rgba(0, 0, 0, 0.5); padding: 0; overflow: hidden;">
            
            <!-- Cart Header -->
            <div style="padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.03);">
                <div class="d-flex justify-between align-center" style="margin-bottom: 5px;">
                    <h3 style="margin:0; color: var(--accent-color); display: flex; align-items: center; gap: 10px; font-size: 1.3rem;">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                    </h3>
                    <span style="background: var(--accent-color); color: #000; padding: 2px 8px; border-radius: 6px; font-weight: bold; font-size: 0.8rem;">#POS</span>
                </div>
                <small id="transaction_date" style="color: var(--text-secondary); display: block; margin-top: 5px; font-family: monospace; font-size: 0.9rem;">
                    <?= date('d M Y H:i') ?>
                </small>
            </div>

            <!-- Cart Items List -->
            <div id="cart-items" style="flex: 1; overflow-y: auto; padding: 0;">
                <!-- Loading State -->
                <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-secondary); opacity: 0.5;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
            
            <!-- Cart Footer (Totals & Actions) -->
            <div style="padding: 20px; background: rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-between align-end" style="margin-bottom: 10px;">
                    <span style="color: var(--text-secondary); font-size: 0.9rem;">Total Tagihan</span>
                    <span class="total-price-display" style="font-size: 1.8rem; font-weight: 800; color: var(--success-color); line-height: 1;">Rp 0</span>
                </div>
                
                <div style="height: 1px; background: rgba(255,255,255,0.1); margin: 15px 0;"></div>
                
                <div style="margin-bottom: 15px;">
                    <div style="position: relative;">
                        <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-weight: bold;">Rp</span>
                        <input type="text" id="inputBayar" class="form-control" placeholder="Input Bayar..." oninput="formatRupiah(this)" style="padding-left: 45px; height: 50px; font-size: 1.2rem; font-weight: bold; border-color: rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: white;">
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-danger" onclick="resetCart()" style="width: 35%; height: 50px; border-radius: 12px; font-weight: bold;">
                        <i class="fas fa-trash-alt"></i> Batal
                    </button>
                    <button class="btn btn-success" onclick="processTransaction()" style="width: 65%; height: 50px; border-radius: 12px; font-weight: bold; font-size: 1.1rem; background: var(--success-color); color: #fff; border: none; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);">
                        <i class="fas fa-receipt" style="margin-right: 8px;"></i> BUAT STRUK
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Scan Error (Red) -->
<div id="scanErrorModal" class="modal-overlay" style="display: none;" onclick="hidePosModal('scanErrorModal')">
    <div class="modal-content" onclick="event.stopPropagation()" style="max-width: 400px;">
        <div class="modal-icon error">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Scan Gagal</h3>
        <p class="modal-message" id="scanErrorMessage">Objek tidak valid.</p>
        <div style="margin-top: 20px;">
            <button class="modal-button btn-error" onclick="hidePosModal('scanErrorModal')">OK</button>
        </div>
    </div>
</div>

<!-- Modal Success (Green) -->
<div id="modalSuccess" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-icon success">
            <i class="fas fa-check"></i>
        </div>
        <h3 class="modal-title" id="successTitle">Berhasil</h3>
        <p class="modal-message" id="successMessage">Operasi berhasil dilakukan.</p>
        <div style="margin-top: 20px;">
            <button class="modal-button btn-success-modal" onclick="hidePosModal('modalSuccess')">OK</button>
        </div>
    </div>
</div>

<!-- Modal Warning (Yellow) -->
<div id="modalWarning" class="modal-overlay" style="display: none;" onclick="hidePosModal('modalWarning')">
    <div class="modal-content" onclick="event.stopPropagation()" style="max-width: 400px;">
        <div class="modal-icon warning">
            <i class="fas fa-exclamation"></i>
        </div>
        <h3 class="modal-title">Perhatian</h3>
        <p class="modal-message" id="warningMessage">Peringatan.</p>
        <div style="margin-top: 20px;">
            <button class="modal-button btn-warning" onclick="hidePosModal('modalWarning')">Mengerti</button>
        </div>
    </div>
</div>

<!-- Modal Confirm (Blue) -->
<div id="modalConfirm" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-icon confirm">
            <i class="fas fa-question"></i>
        </div>
        <h3 class="modal-title" id="confirmTitle">Konfirmasi</h3>
        <p class="modal-message" id="confirmMessage">Apakah Anda yakin?</p>
        <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: center;">
            <button class="modal-button btn-secondary" onclick="hidePosModal('modalConfirm')" style="background: rgba(255,255,255,0.1);">Batal</button>
            <button class="modal-button btn-confirm" id="btnConfirmYes">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<!-- Scanner Lock Indicator -->
<div id="scannerLockIndicator" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; background: rgba(0,0,0,0.9); padding: 30px; border-radius: 16px; border: 2px solid var(--accent-color); text-align: center;">
    <div class="spinner" style="width: 60px; height: 60px; border: 4px solid rgba(0,212,255,0.3); border-top: 4px solid var(--accent-color); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 15px;"></div>
    <p style="color: var(--accent-color); font-weight: bold; margin: 0;">Memproses Scan...</p>
    <small style="color: var(--text-secondary);">Validasi barcode/QR code</small>
</div>

<!-- STYLES -->
<style>
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
@keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

/* Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

/* Modal Styles Unified */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center;
    z-index: 10000; animation: fadeIn 0.2s ease;
}
/* Z-Index Hierarchy */
#scanErrorModal { z-index: 10005; }
#modalWarning { z-index: 10006; }
#modalConfirm { z-index: 10007; }
#modalSuccess { z-index: 10010; } /* Paling Tinggi */

.modal-content {
    background: linear-gradient(135deg, #1e1e1e, #141414);
    border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px;
    text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.6);
    animation: slideUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-origin: center;
    position: relative; /* Ensure clicks works */
    pointer-events: auto;
}
.modal-icon {
    width: 70px; height: 70px; margin: 0 auto 20px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px;
}
.modal-icon.error { background: rgba(255,69,58,0.1); color: #ff453a; border: 2px solid rgba(255,69,58,0.2); }
.modal-icon.success { background: rgba(46,204,113,0.1); color: #2ecc71; border: 2px solid rgba(46,204,113,0.2); }
.modal-icon.warning { background: rgba(255,193,7,0.1); color: #ffc107; border: 2px solid rgba(255,193,7,0.2); }
.modal-icon.confirm { background: rgba(0,212,255,0.1); color: var(--accent-color); border: 2px solid rgba(0,212,255,0.2); }

.modal-title { margin: 0 0 10px; color: #fff; font-size: 1.4rem; letter-spacing: 0.5px; }
.modal-message { color: #aaa; margin: 0 auto 20px; line-height: 1.5; font-size: 0.95rem; }

.modal-button {
    border: none; padding: 12px 30px; border-radius: 12px; font-weight: bold; cursor: pointer; transition: all 0.2s; font-size: 1rem;
    position: relative; z-index: 10; /* Force accessible */
}
.modal-button:hover { transform: translateY(-2px); filter: brightness(1.1); }
.modal-button:active { transform: translateY(0); }

.btn-error { background: #ff453a; color: white; box-shadow: 0 4px 15px rgba(255, 69, 58, 0.3); }
.btn-success-modal { background: #2ecc71; color: white; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3); }
.btn-warning { background: #ffc107; color: black; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3); }
.btn-confirm { background: var(--accent-color); color: black; box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3); }
.btn-secondary { color: #fff; }

.btn-confirm { background: var(--accent-color); color: black; box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3); }
.btn-secondary { color: #fff; }

/* Custom POS Header Responsive Theme */
.custom-pos-header {
    background: rgba(30, 30, 30, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
html[data-theme="light"] .custom-pos-header {
    background: rgba(255, 255, 255, 0.85) !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    color: #333 !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
html[data-theme="light"] .custom-pos-header h3 { color: #000 !important; }
html[data-theme="light"] .custom-pos-header small { color: #666 !important; }

</style>

<!-- SCRIPTS -->
<script>
// --- VARIABEL GLOBAL ---
let html5QrcodeScanner = null;
let isScanning = false; 
const beepSound = new Audio('../../assets/audio/beep.mp3');
// Simple Error Beep (Base64)
const errorSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGWi77eeeTRAMUKfj8LZjHAY4ktfyzHksBSR3x/DdkEAKFF606+uoVRQKRp/g8r5sIQUrgs7y2Yk2CBlou+3nnk0QDFCn4/C2YxwGOJLX8sx5LAUkd8fw3ZBAC');

document.addEventListener('DOMContentLoaded', () => {
    updateCartUI(); 
    if (typeof Html5Qrcode === 'undefined') console.error('Html5Qrcode NOT loaded!');
});

// --- HELPER MODAL FUNCTIONS (RENAMED TO AVOID CONFLICTS) ---
function hidePosModal(id) {
    console.log("Closing modal: " + id);
    const el = document.getElementById(id);
    if(el) el.style.display = 'none';
    
    // Khusus Scan Error, resume scanner
    if (id === 'scanErrorModal') {
        const readerDiv = document.getElementById('reader');
        if (readerDiv) { readerDiv.style.opacity = '1'; readerDiv.style.pointerEvents = 'auto'; }
        if (html5QrcodeScanner && isScanning) {
            setTimeout(() => { html5QrcodeScanner.resume(); isScanning = false; }, 500);
        }
    }
}

function displayModalWarning(msg) {
    document.getElementById('warningMessage').textContent = msg;
    document.getElementById('modalWarning').style.display = 'flex';
    errorSound.play().catch(()=>{});
}

function displayModalSuccess(title, msg) {
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successMessage').textContent = msg;
    document.getElementById('modalSuccess').style.display = 'flex';
    beepSound.play().catch(()=>{});
}

function displayModalConfirm(title, msg, onYes) {
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = msg;
    document.getElementById('modalConfirm').style.display = 'flex';
    
    // Setup tombol Yes
    const btnYes = document.getElementById('btnConfirmYes');
    // Hapus event listener lama agar tdk double
    const newBtn = btnYes.cloneNode(true);
    btnYes.parentNode.replaceChild(newBtn, btnYes);
    
    newBtn.onclick = function() {
        hidePosModal('modalConfirm');
        onYes();
    };
}

// --- SCANNER LOGIC ---
function openCamera() {
    const scannerContainer = document.getElementById('camera-preview');
    if (scannerContainer.style.display === 'none' || scannerContainer.style.display === '') {
        scannerContainer.style.display = 'block';
        if (html5QrcodeScanner) html5QrcodeScanner.stop().then(initScanner).catch(initScanner);
        else initScanner();
    } else {
        stopCamera();
    }
}

function initScanner() {
    try {
        html5QrcodeScanner = new Html5Qrcode("reader");
        const config = { fps: 20, qrbox: { width: 300, height: 150 }, experimentalFeatures: { useBarCodeDetectorIfSupported: true } };
        html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
        .catch(err => { displayModalWarning("Gagal kamera: " + err); stopCamera(); });
    } catch (e) { displayModalWarning('Error init: '+e); }
}

function stopCamera() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => { document.getElementById('camera-preview').style.display = 'none'; })
        .catch(() => { document.getElementById('camera-preview').style.display = 'none'; });
    } else {
        document.getElementById('camera-preview').style.display = 'none';
    }
}
// --- LOCK UI FUNCTIONS ---
function showScannerLock() {
    document.getElementById('scannerLockIndicator').style.display = 'block';
}

function hideScannerLock() {
    document.getElementById('scannerLockIndicator').style.display = 'none';
}

function onScanSuccess(decodedText, decodedResult) {
    console.log("Scan Success:", decodedText); // Debugging
    if (isScanning) return;
    isScanning = true;
    
    beepSound.play().catch(e => console.log('Audio error:', e));
    try { html5QrcodeScanner.pause(true); } catch(e){}
    
    const readerDiv = document.getElementById('reader');
    if(readerDiv) { readerDiv.style.opacity = '0.5'; readerDiv.style.pointerEvents = 'none'; }
    
    showScannerLock(); // Spinner
    
    // Validasi panjang minimum
    if (!decodedText || decodedText.trim().length === 0) {
        console.warn("Empty barcode detected");
        handleScanError('Kode kosong/tidak valid');
        return;
    }
    
    addToCartByCode(decodedText);
}

function handleScanError(msg) {
    setTimeout(() => {
        hideScannerLock();
        const readerDiv = document.getElementById('reader');
        if(readerDiv) { readerDiv.style.opacity='1'; readerDiv.style.pointerEvents='auto'; }
        
        document.getElementById('scanErrorMessage').textContent = msg;
        document.getElementById('scanErrorModal').style.display = 'flex';
        errorSound.play().catch(()=>{});
        
        // Reset isScanning di hidePosModal
    }, 800);
}

function onScanFailure(error) {}

// --- FORMAT RUPIAH ---
function formatRupiah(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) input.value = new Intl.NumberFormat('id-ID').format(value);
    else input.value = '';
}

// --- TRANSACTION LOGIC ---
function processTransaction() {
    let bayarInput = document.getElementById('inputBayar').value.replace(/\./g, '');
    let bayar = parseInt(bayarInput) || 0;
    
    const textTotal = document.querySelector('.total-price-display').innerText.replace(/[^0-9]/g, '');
    const totalInt = parseInt(textTotal) || 0;
    
    if (totalInt === 0) {
        displayModalWarning("Keranjang masih kosong!");
        return;
    }

    if (bayar <= 0) {
        displayModalWarning("Mohon masukkan jumlah pembayaran!");
        setTimeout(() => document.getElementById('inputBayar').focus(), 500); // Focus balikin ke input
        return;
    }
    
    if (bayar < totalInt) {
         displayModalWarning("Uang pembayaran kurang! Total: " + new Intl.NumberFormat('id-ID').format(totalInt));
         return;
    }

    displayModalConfirm("Konfirmasi", "Proses transaksi dan cetak struk?", function() {
        processCheckout(bayar);
    });
}

function processCheckout(nominalBayar) {
    const formData = new FormData();
    formData.append('bayar', nominalBayar);
    
    fetch('api/api_checkout.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("Server Raw Response:", text);
                // Jika respon bukan JSON (misal error PHP), tampilkan potongan errornya
                throw new Error(text.replace(/<[^>]*>?/gm, '').substring(0, 150)); 
            }
        });
    })
    .then(data => {
        if(data.status === 'success') {
            const kembalian = data.kembalian;
            const strukUrl = 'struk/cetak.php?id=' + data.id_transaksi;
            
            updateCartUI();
            document.getElementById('inputBayar').value = ''; 
            
            displayModalSuccess("TRANSAKSI SUKSES!", "Kembalian: Rp " + new Intl.NumberFormat('id-ID').format(kembalian));
            window.open(strukUrl, '_blank', 'width=400,height=600');
            
        } else {
            displayModalWarning(data.message);
        }
    })
    .catch(err => {
        console.error(err);
        displayModalWarning("Gagal: " + err.message);
    });
}

// --- CART LOGIC ---
function addToCart(id_produk) {
    fetch('api/api_cart.php?act=add&id=' + id_produk)
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                updateCartUI();
                beepSound.play().catch(()=>{}); // Feedback suara
            }
            else displayModalWarning(data.message);
        });
}

// Fitur Baru: Update Quantity (+ / -)
function updateQty(id, change) {
    fetch('api/api_cart.php?act=update_qty&id=' + id + '&change=' + change)
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                updateCartUI();
            } else {
                displayModalWarning(data.message);
            }
        })
        .catch(err => {
            console.error(err);
        });
}

function addToCartByCode(code) {
    // Encode code to handle special characters safely
    fetch('api/api_cart.php?act=add_by_code&code=' + encodeURIComponent(code))
        .then(res => res.json())
        .then(data => {
            hideScannerLock();
            const readerDiv = document.getElementById('reader');
            if(readerDiv) { readerDiv.style.opacity='1'; readerDiv.style.pointerEvents='auto'; }
            
            if(data.status === 'success') {
                updateCartUI();
                beepSound.play().catch(()=>{});
                setTimeout(() => {
                    if (html5QrcodeScanner) { html5QrcodeScanner.resume(); }
                    isScanning = false;
                }, 1000);
            } else if (data.status === 'not_found') {
                // Panggil Modal Error
                document.getElementById('scanErrorMessage').textContent = 'Produk tidak ditemukan.';
                document.getElementById('scanErrorModal').style.display = 'flex';
                errorSound.play().catch(()=>{});
            } else {
                // Panggil Modal Error
                document.getElementById('scanErrorMessage').textContent = data.message;
                document.getElementById('scanErrorModal').style.display = 'flex';
                errorSound.play().catch(()=>{});
            }
        })
        .catch(err => {
            hideScannerLock();
             // Panggil Modal Error
            document.getElementById('scanErrorMessage').textContent = 'Error koneksi.';
            document.getElementById('scanErrorModal').style.display = 'flex';
        });
}

function updateCartUI() {
    fetch('api/api_cart.php?act=view')
        .then(res => res.text())
        .then(html => {
            document.getElementById('cart-items').innerHTML = html;
            fetch('api/api_cart.php?act=total')
                .then(res => res.json())
                .then(resData => {
                    document.querySelector('.total-price-display').innerText = "Rp " + resData.total_formatted;
                });
        });
}

function removeFromCart(id) {
    fetch('api/api_cart.php?act=remove&id=' + id).then(r => r.json()).then(updateCartUI);
}

function resetCart() {
    // Cek dulu apakah cart kosong?
    const textTotal = document.querySelector('.total-price-display').innerText.replace(/[^0-9]/g, '');
    if (parseInt(textTotal) === 0) return; // Silent return
    
    displayModalConfirm("Kosongkan Keranjang", "Semua item akan dihapus. Lanjutkan?", function() {
        fetch('api/api_cart.php?act=reset').then(r => r.json()).then(updateCartUI);
    });
}

// --- SEARCH & FILTER LOGIC ---
const searchInput = document.getElementById('searchProduct');

// 1. Shortcut F2
document.addEventListener('keydown', function(event) {
    if (event.key === "F2") {
        event.preventDefault();
        searchInput.focus();
    }
});

// 2. Input Event (Visual Filter)
searchInput.addEventListener('input', function(e) {
    const term = this.value.toLowerCase();
    const cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        const name = card.querySelector('.product-info h5').innerText.toLowerCase();
        // Kita simpan display asli jika belum ada, tapi untuk grid item biasanya cukup display 'none' vs default
        if (name.includes(term)) {
            card.style.display = ''; // Reset ke default css
        } else {
            card.style.display = 'none';
        }
    });
});

// 3. Enter Key (Add by Code/Name exact match attempt)
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query) {
            // Coba add to cart (API akan handle cari by kode dulu)
            // Kita reset filter dulu biar user liat feedback
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(c => c.style.display = ''); 
            this.value = '';
            
            addToCartByCode(query);
        }
    }
});
</script>

<?php 
// Close .main-content (opened in header logic)
echo '</div>'; 
include '../../template/footer.php'; 
?>
