<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic
include '../../template/header.php';
include '../../template/sidebar.php';
?>

<div class="d-flex" style="height: calc(100vh - 40px); gap: 20px;">
    
    <!-- Bagian Kiri: List Produk -->
    <div style="flex: 2; display: flex; flex-direction: column;">
        
        <!-- Search & Filter -->
        <div class="glass-panel" style="margin-bottom: 20px; padding: 15px;">
            <div class="d-flex gap-2">
                <input type="text" id="searchProduct" class="form-control" placeholder="Cari Kode / Nama Produk (F2)..." style="flex: 1;" autofocus>
                <button class="btn btn-primary" onclick="openCamera()"><i class="fas fa-camera"></i> Scan</button>
            </div>
            <!-- Area Kamera -->
            <div id="camera-preview" style="display:none; margin-top: 15px; text-align: center;">
                <div id="reader" style="width: 100%; max-width: 400px; margin: 0 auto; border-radius: 8px; overflow: hidden;"></div>
                <small style="color: var(--text-secondary);">Arahkan kamera ke barcode</small>
            </div>
        </div>

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
                    <p style="color: var(--accent-color); font-weight: bold;">Rp <?= number_format($p['harga_jual']) ?></p>
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

<!-- Modal / Script Scanner & Cart Logic -->
<script>
// --- VARIABEL GLOBAL ---
let html5QrcodeScanner = null;
const beepSound = new Audio('../../assets/audio/beep.mp3'); // Pastikan file audio ada atau gunakan URL online dummy dulu
// Fallback dummy audio (opsional jika file belum ada)
// beepSound.src = "https://www.soundjay.com/buttons/sounds/button-3.mp3"; 

// --- SCANNER LOGIC ---
function openCamera() {
    const scannerContainer = document.getElementById('camera-preview');
    const readerDiv = document.getElementById('reader');
    
    // Toggle Tampilan
    if (scannerContainer.style.display === 'none') {
        scannerContainer.style.display = 'block';
        
        // Init Library
        html5QrcodeScanner = new Html5Qrcode("reader");
        
        // Config: FPS lebih tinggi agar 'langsung terbaca'
        // qrbox lebih lebar agar cocok untuk barcode panjang
        const config = { 
            fps: 20, 
            qrbox: { width: 300, height: 150 },
            aspectRatio: 1.0,
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        };
        
        // Start Camera (Environment = Kamera Belakang)
        html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => {
            console.error("Gagal membuka kamera:", err);
            alert("Gagal membuka kamera. Pastikan izin akses diberikan dan menggunakan HTTPS/Localhost.");
        });
        
    } else {
        // Jika sudah terbuka, tutup
        stopCamera();
    }
}

function stopCamera() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            document.getElementById('camera-preview').style.display = 'none';
        }).catch(err => {
            console.log("Stop failed", err);
            // Hide anyway
             document.getElementById('camera-preview').style.display = 'none';
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {
    // 1. Mainkan Suara
    beepSound.play().catch(e => console.log("Audio play failed", e));
    
    // 2. Masukkan ke Keranjang via AJAX
    addToCartByCode(decodedText);
    
    // Opsional: Pause sebentar agar tidak double scan cepat (tapi tetap responsif)
    html5QrcodeScanner.pause();
    setTimeout(() => {
        html5QrcodeScanner.resume();
    }, 700);
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
    fetch('api/api_cart.php?act=add_by_code&code=' + code)
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                updateCartUI();
                beepSound.play();
            } else if (data.status === 'not_found') {
                // Logic Cerdas: Jika produk tidak ada, tawarkan tambah produk
                // Gunakan confirm atau langsung redirect (tergantung preferensi, confirm lebih aman agar tidak kaget)
                if(confirm("Produk belum terdaftar. Tambah data produk baru sekarang?")) {
                     window.location.href = "../produk/form.php?code=" + encodeURIComponent(code);
                }
            } else {
                alert("Gagal: " + data.message);
            }
        })
        .catch(err => console.error(err));
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
