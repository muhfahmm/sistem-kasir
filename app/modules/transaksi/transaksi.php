<?php
require_once '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic
require_once '../../config/role_helper.php'; // Helper functions untuk role

// Cek apakah user adalah admin (role aktif = admin)
$is_admin = isActiveAdmin();

include '../../template/header.php';

// Jika Admin, tampilkan Sidebar. Jika Kasir, Full Screen Mode.
if ($is_admin) {
    include '../../template/sidebar.php';
} else {
    // Style override khusus Kasir Mode agar full width tanpa margin sidebar
    echo '<style> .main-content { margin-left: 0; padding: 20px; } </style>';
    // Header minimalis untuk kasir (Logout & Nama)
    echo '<div class="glass-header d-flex justify-between align-center" style="padding: 15px 20px; margin-bottom: 20px; border-radius: 12px;">
            <div class="d-flex align-center gap-2">
                <i class="fas fa-bolt" style="color: var(--accent-color); font-size: 1.5rem;"></i>
                <h3 style="margin:0;">KASIR MODE</h3>
            </div>
            <div class="d-flex align-center gap-2">
                <span>Hi, '. $_SESSION['nama_lengkap'] .'</span>
                <a href="../../modules/auth/api/logout.php" class="btn btn-danger btn-sm"><i class="fas fa-power-off"></i></a>
            </div>
          </div>';
}
?>

<div class="d-flex" style="height: calc(100vh - 40px); gap: 20px;">
    
    <!-- Bagian Kiri: List Produk -->
    <div style="flex: 2; display: flex; flex-direction: column;">
        
        <!-- Search & Filter Area -->
        <div class="glass-panel" style="margin-bottom: 20px; padding: 15px;">
            <div class="d-flex gap-2">
                <input type="text" id="searchProduct" class="form-control" placeholder="Cari Kode / Nama Produk (F2)..." style="flex: 1;" autofocus>
                <!-- Tombol Scan tetep ada, tapi nanti kita bisa auto-open untuk kasir -->
                <button class="btn btn-primary" onclick="openCamera()"><i class="fas fa-camera"></i> Scan</button>
            </div>
            
            <!-- Area Kamera -->
            <div id="camera-preview" style="display: none; margin-top: 15px; text-align: center;">
                <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto; border-radius: 8px; overflow: hidden; background: #000;"></div>
                <small style="color: var(--text-secondary); display: block; margin-top: 10px;">Arahkan kamera ke barcode/QR code</small>
            </div>
        </div>

        <!-- Logic Auto Start Camera for Kasir - DISABLED, gunakan manual saja -->
        <?php /* if(!$is_admin): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Auto start camera for Kasir role after short delay
                setTimeout(openCamera, 1000);
            });
        </script>
        <?php endif; */ ?>

        <!-- Grid Produk -->
        <div style="flex: 1; overflow-y: auto; padding-right: 5px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px;">
                <?php
                $produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");
                while($p = mysqli_fetch_assoc($produk)):
                ?>
                <div class="glass-panel" onclick="addToCart(<?= $p['id_produk'] ?>)" style="padding: 10px; cursor: pointer; text-align: center; transition: all 0.2s;">
                    <div style="height: 80px; background: rgba(255,255,255,0.05); margin-bottom: 10px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-box fa-2x" style="color: var(--text-secondary);"></i>
                    </div>
                    <h5 style="margin-bottom: 5px; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $p['nama_produk'] ?></h5>
                    <p style="color: var(--accent-color); font-weight: bold;">Rp <?= number_format($p['harga']) ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Bagian Kanan: Keranjang / Receip -->
    <div class="glass-panel" style="flex: 1; display: flex; flex-direction: column; background: rgba(18,18,18,0.9);">
        
        <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 15px; margin-bottom: 15px;">
            <h3 style="color: var(--accent-color);"><i class="fas fa-shopping-cart"></i> Keranjang</h3>
            <small id="transaction_date"><?= date('d M Y H:i') ?></small>
        </div>

        <!-- List Item Keranjang (Dummy JS) -->
        <div id="cart-items" style="flex: 1; overflow-y: auto; margin-bottom: 15px;">
            <div style="text-align: center; color: var(--text-secondary); margin-top: 50px;">
                <i class="fas fa-basket-shopping fa-3x" style="opacity: 0.3;"></i>
                <p style="margin-top: 10px;">Keranjang Kosong</p>
            </div>
        </div>
        
        <!-- Kalkulasi -->
        <div style="border-top: 1px solid var(--border-glass); padding-top: 15px;">
            <div class="d-flex justify-between" style="margin-bottom: 10px;">
                <span>Total</span>
                <span class="total-price-display" style="font-size: 1.5rem; font-weight: bold; color: var(--success-color);">Rp 0</span>
            </div>
            <div style="margin-bottom: 15px;">
                <input type="number" class="form-control" placeholder="Bayar (Rp)...">
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-danger w-100" onclick="resetCart()" style="justify-content: center;">Batal</button>
                <button class="btn btn-primary w-100" style="justify-content: center;"><i class="fas fa-print"></i> Bayar</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal Scan Error -->
<div id="scanErrorModal" class="modal-overlay" style="display: none;" onclick="closeScanErrorModal()">
    <div class="modal-content" onclick="event.stopPropagation()" style="max-width: 400px;">
        <div class="modal-icon error">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Scan Gagal</h3>
        <p class="modal-message" id="scanErrorMessage">Objek yang di-scan bukan barcode/QR code yang valid.</p>
        <div style="margin-top: 20px;">
            <button class="modal-button" onclick="closeScanErrorModal()">OK</button>
        </div>
    </div>
</div>

<!-- Scanner Lock Indicator -->
<div id="scannerLockIndicator" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; background: rgba(0,0,0,0.9); padding: 30px; border-radius: 16px; border: 2px solid var(--accent-color); text-align: center;">
    <div class="spinner" style="width: 60px; height: 60px; border: 4px solid rgba(0,212,255,0.3); border-top: 4px solid var(--accent-color); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 15px;"></div>
    <p style="color: var(--accent-color); font-weight: bold; margin: 0;">Memproses Scan...</p>
    <small style="color: var(--text-secondary);">Validasi barcode/QR code</small>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: linear-gradient(135deg, rgba(30,30,30,0.95), rgba(20,20,20,0.95));
    border: 1px solid rgba(0,212,255,0.3);
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    animation: slideUp 0.3s ease;
}

.modal-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
}

.modal-icon.error {
    background: rgba(255,69,58,0.2);
    color: #ff453a;
}

.modal-title {
    margin: 0 0 10px;
    color: #fff;
}

.modal-message {
    color: var(--text-secondary);
    margin: 0 0 20px;
}

.modal-button {
    background: var(--accent-color);
    color: #000;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.modal-button:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(0,212,255,0.4);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<!-- Modal / Script Scanner & Cart Logic -->
<script>
// --- VARIABEL GLOBAL ---
let html5QrcodeScanner = null;
let isScanning = false; // Flag untuk prevent double scan
const beepSound = new Audio('../../assets/audio/beep.mp3');
const errorSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGWi77eeeTRAMUKfj8LZjHAY4ktfyzHksBSR3x/DdkEAKFF606+uoVRQKRp/g8r5sIQUrgs7y2Yk2CBlou+3nnk0QDFCn4/C2YxwGOJLX8sx5LAUkd8fw3ZBAC'); // Beep error

// Check if Html5Qrcode library is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Page loaded, checking Html5Qrcode library...');
    if (typeof Html5Qrcode === 'undefined') {
        console.error('Html5Qrcode library NOT loaded!');
        alert('Error: Scanner library tidak ter-load!\n\nSolusi:\n1. Refresh halaman (F5)\n2. Clear browser cache\n3. Cek koneksi internet');
    } else {
        console.log('Html5Qrcode library loaded successfully!');
    }
});

// --- MODAL FUNCTIONS ---
function showScanErrorModal(message) {
    document.getElementById('scanErrorMessage').textContent = message;
    document.getElementById('scanErrorModal').style.display = 'flex';
    errorSound.play().catch(e => console.log('Error sound failed', e));
}

function closeScanErrorModal() {
    document.getElementById('scanErrorModal').style.display = 'none';
    
    // Restore camera opacity
    const readerDiv = document.getElementById('reader');
    if (readerDiv) {
        readerDiv.style.opacity = '1';
        readerDiv.style.pointerEvents = 'auto';
    }
    
    // Resume scanner setelah modal ditutup
    if (html5QrcodeScanner && isScanning) {
        setTimeout(() => {
            html5QrcodeScanner.resume();
            console.log('🔓 Scanner resumed after modal close');
            isScanning = false;
        }, 500);
    }
}

function showScannerLock() {
    document.getElementById('scannerLockIndicator').style.display = 'block';
}

function hideScannerLock() {
    document.getElementById('scannerLockIndicator').style.display = 'none';
}

// --- SCANNER LOGIC ---
function openCamera() {
    console.log('openCamera() called');
    const scannerContainer = document.getElementById('camera-preview');
    
    if (!scannerContainer) {
        console.error('Element camera-preview not found!');
        alert('Error: Camera container not found!');
        return;
    }
    
    console.log('Current display:', scannerContainer.style.display);
    
    if (scannerContainer.style.display === 'none' || scannerContainer.style.display === '') {
        console.log('Opening camera...');
        scannerContainer.style.display = 'block';
        
        if (html5QrcodeScanner) {
            console.log('Scanner already initialized, stopping first...');
            html5QrcodeScanner.stop().then(() => {
                initScanner();
            }).catch(err => {
                console.log('Stop error (ignored):', err);
                initScanner();
            });
        } else {
            initScanner();
        }
    } else {
        console.log('Closing camera...');
        stopCamera();
    }
}

function initScanner() {
    console.log('Initializing scanner...');
    const scannerContainer = document.getElementById('camera-preview');
    
    try {
        html5QrcodeScanner = new Html5Qrcode("reader");
        console.log('Html5Qrcode instance created');
        
        const config = { 
            fps: 20, 
            qrbox: { width: 300, height: 150 },
            aspectRatio: 1.0,
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        };
        
        console.log('Starting camera with config:', config);
        
        html5QrcodeScanner.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess, 
            onScanFailure
        )
        .then(() => {
            console.log('Camera started successfully!');
        })
        .catch(err => {
            console.error("Failed to start camera:", err);
            alert("Gagal membuka kamera: " + err.message + "\n\nPastikan:\n1. Browser Chrome/Edge/Firefox\n2. Izin kamera di-allow\n3. Akses dari localhost");
            if (scannerContainer) {
                scannerContainer.style.display = 'none';
            }
        });
    } catch (error) {
        console.error('Error in initScanner:', error);
        alert('Error initializing scanner: ' + error.message);
    }
}

function stopCamera() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            document.getElementById('camera-preview').style.display = 'none';
        }).catch(err => {
            console.log("Stop failed", err);
            document.getElementById('camera-preview').style.display = 'none';
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {
    console.log('🎯 SCAN DETECTED:', decodedText);
    
    if (isScanning) {
        console.log('⚠️ Already scanning, ignored');
        return; // Prevent double scan
    }
    
    isScanning = true;
    console.log('🔒 SCANNER LOCKED');
    
    // HARD PAUSE - Stop scanner immediately
    try {
        html5QrcodeScanner.pause(true); // Force pause
        console.log('✅ Scanner paused');
    } catch (e) {
        console.log('⚠️ Pause error:', e);
    }
    
    // Show lock indicator
    showScannerLock();
    
    // Add visual freeze overlay on camera
    const readerDiv = document.getElementById('reader');
    if (readerDiv) {
        readerDiv.style.opacity = '0.5';
        readerDiv.style.pointerEvents = 'none';
    }
    
    // Validasi: Cek apakah hasil scan valid (minimal 3 karakter)
    if (!decodedText || decodedText.trim().length < 3) {
        console.log('❌ Code too short:', decodedText);
        setTimeout(() => {
            hideScannerLock();
            if (readerDiv) {
                readerDiv.style.opacity = '1';
                readerDiv.style.pointerEvents = 'auto';
            }
            showScanErrorModal('Kode yang di-scan terlalu pendek atau tidak valid.');
            isScanning = false;
        }, 800);
        return;
    }
    
    console.log('✅ Code valid, processing...');
    
    // Play beep sound
    beepSound.play().catch(e => console.log("Audio play failed", e));
    
    // Proses scan ke server
    addToCartByCode(decodedText);
}

function onScanFailure(error) {
    // Callback ini dipanggil setiap frame yang gagal detect
    // Kita tidak perlu handle di sini karena terlalu sering
    // console.log("Scan failed:", error);
}

// --- CART LOGIC ---

// Tambah via Klik Manual
function addToCart(id_produk) {
    // Kirim AJAX ke server
    fetch('api/api_cart.php?act=add&id=' + id_produk)
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                updateCartUI(); 
            } else {
                alert(data.message);
            }
        });
}

// Tambah via Barcode
function addToCartByCode(code) {
    console.log('📡 Sending to server:', code);
    const readerDiv = document.getElementById('reader');
    
    fetch('api/api_cart.php?act=add_by_code&code=' + code)
        .then(response => response.json())
        .then(data => {
            console.log('📥 Server response:', data);
            hideScannerLock();
            
            // Restore camera opacity
            if (readerDiv) {
                readerDiv.style.opacity = '1';
                readerDiv.style.pointerEvents = 'auto';
            }
            
            if(data.status === 'success') {
                console.log('✅ Product added to cart');
                updateCartUI();
                beepSound.play();
                // Resume scanner setelah sukses
                setTimeout(() => {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.resume();
                        console.log('🔓 Scanner resumed');
                    }
                    isScanning = false;
                }, 1000);
            } else if (data.status === 'not_found') {
                console.log('❌ Product not found');
                showScanErrorModal('Produk dengan kode "' + code + '" tidak ditemukan di database.');
                // Modal akan handle resume scanner saat ditutup
            } else {
                console.log('❌ Error:', data.message);
                showScanErrorModal('Gagal menambahkan produk: ' + data.message);
            }
        })
        .catch(err => {
            console.error('❌ Network error:', err);
            hideScannerLock();
            
            // Restore camera opacity
            if (readerDiv) {
                readerDiv.style.opacity = '1';
                readerDiv.style.pointerEvents = 'auto';
            }
            
            showScanErrorModal('Terjadi kesalahan koneksi ke server.');
        });
}

// Update Tampilan Keranjang
function updateCartUI() {
    fetch('api/api_cart.php?act=view')
        .then(response => response.text())
        .then(html => {
            document.getElementById('cart-items').innerHTML = html;
            
            // Update Total
            fetch('api/api_cart.php?act=total')
                .then(res => res.json())
                .then(resData => {
                    document.querySelector('.total-price-display').innerText = "Rp " + resData.total_formatted;
                });
        });
}

// Hapus Item
function removeFromCart(id) {
    fetch('api/api_cart.php?act=remove&id=' + id)
        .then(res => res.json())
        .then(data => {
             updateCartUI();
        });
}

// Reset Keranjang (Tombol Batal)
function resetCart() {
    if(confirm('Kosongkan keranjang?')) {
        fetch('api/api_cart.php?act=reset')
            .then(res => res.json())
            .then(data => {
                 updateCartUI();
            });
    }
}

// Init pertama kali load
document.addEventListener('DOMContentLoaded', updateCartUI);

// Shortcut Keyboard F2 untuk Search
document.addEventListener('keydown', function(event) {
    if (event.key === "F2") {
        document.getElementById('searchProduct').focus();
    }
});
</script>

<?php include '../../template/footer.php'; ?>
