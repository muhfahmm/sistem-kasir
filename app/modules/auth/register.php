<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}
require_once '../../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    
    <div class="glass-panel fade-in" style="width: 100%; max-width: 400px; padding: 40px; text-align: center;">
        <h2 style="margin-bottom: 20px; color: var(--accent-color);">Daftar Akun</h2>
        <p style="color: var(--text-secondary); margin-bottom: 30px;">Buat akun baru untuk masuk.</p>

        <form action="api/proses_register.php" method="POST">
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="display: block; margin-bottom: 8px;">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" required placeholder="Nama Anda">
            </div>
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="display: block; margin-bottom: 8px;">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="User baru">
            </div>
            <div style="margin-bottom: 20px; text-align: left;">
                <label style="display: block; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Password kuat">
            </div>
            
            <div style="margin-bottom: 20px; text-align: left;">
                <label style="display: block; margin-bottom: 12px; font-weight: 500;">Pilih Role (minimal 1)</label>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px; transition: all 0.3s;">
                        <input type="checkbox" name="roles[]" value="admin" style="margin-right: 10px; cursor: pointer; width: 18px; height: 18px;">
                        <span>Admin - Akses penuh ke semua fitur</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px; transition: all 0.3s;">
                        <input type="checkbox" name="roles[]" value="kasir" style="margin-right: 10px; cursor: pointer; width: 18px; height: 18px;">
                        <span>Kasir - Akses transaksi penjualan</span>
                    </label>
                </div>
                <small style="color: var(--text-secondary); display: block; margin-top: 8px;">Anda bisa memilih kedua role sekaligus</small>
            </div>
            
            <button type="submit" class="btn btn-primary w-100" style="justify-content: center;">REGISTER</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="login.php" style="color: var(--text-secondary); text-decoration: none;">Sudah punya akun? Login</a>
        </div>
    </div>

    <!-- Modal Error/Success -->
    <?php if(isset($_GET['error']) || isset($_GET['success'])): ?>
    <div class="modal-overlay" id="modalNotif" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <?php if(isset($_GET['error'])): ?>
                <div class="modal-icon error"><i class="fas fa-times-circle"></i></div>
                <h3 class="modal-title">Registrasi Gagal</h3>
                <p class="modal-message"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php else: ?>
                <div class="modal-icon success"><i class="fas fa-check-circle"></i></div>
                <h3 class="modal-title">Registrasi Berhasil!</h3>
                <p class="modal-message"><?= htmlspecialchars($_GET['success']) ?></p>
            <?php endif; ?>
            <button class="modal-button" onclick="closeModal()">OK</button>
        </div>
    </div>
    <script>
        function closeModal() {
            document.getElementById('modalNotif').style.display = 'none';
            // Remove query params from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // Validasi form - minimal 1 role harus dipilih
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="roles[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 role (Admin atau Kasir)');
                return false;
            }
        });

        // Hover effect untuk checkbox labels
        document.querySelectorAll('label[style*="cursor: pointer"]').forEach(label => {
            label.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(255,255,255,0.1)';
            });
            label.addEventListener('mouseleave', function() {
                this.style.background = 'rgba(255,255,255,0.05)';
            });
        });
    </script>
    <?php endif; ?>

</body>
</html>
