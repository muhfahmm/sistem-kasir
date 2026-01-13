<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}
require_once '../../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    
    <div class="glass-panel fade-in" style="width: 100%; max-width: 400px; padding: 40px; text-align: center;">
        <h2 style="margin-bottom: 20px; color: var(--accent-color);">Selamat Datang</h2>
        <p style="color: var(--text-secondary); margin-bottom: 30px;">Silakan login untuk melanjutkan.</p>

        <form action="api/proses_login.php" method="POST">
            <div style="margin-bottom: 20px; text-align: left;">
                <label style="display: block; margin-bottom: 8px;">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="User: admin / kasir">
            </div>
            <div style="margin-bottom: 20px; text-align: left;">
                <label style="display: block; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Pass: 123">
            </div>
            <div style="margin-bottom: 30px; text-align: left;">
                <label style="display: block; margin-bottom: 8px;">Login Sebagai</label>
                <select name="role" class="form-control">
                    <option value="kasir">Kasir (POS Mode)</option>
                    <option value="admin">Admin (Dashboard)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="justify-content: center;">LOGIN</button>
        </form>
        <div style="margin-top: 20px;">
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Belum punya akun? <a href="register.php" style="color: var(--accent-color); text-decoration: none; font-weight: 500;">Daftar Sekarang</a></p>
        </div>
    </div>

    <!-- Modal Error/Success -->
    <?php if(isset($_GET['error']) || isset($_GET['success'])): ?>
    <div class="modal-overlay" id="modalNotif" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <?php if(isset($_GET['error'])): ?>
                <div class="modal-icon error"><i class="fas fa-times-circle"></i></div>
                <h3 class="modal-title">Login Gagal</h3>
                <p class="modal-message"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php else: ?>
                <div class="modal-icon success"><i class="fas fa-check-circle"></i></div>
                <h3 class="modal-title">Berhasil!</h3>
                <p class="modal-message"><?= htmlspecialchars($_GET['success']) ?></p>
            <?php endif; ?>
            <button class="modal-button" onclick="closeModal()">OK</button>
        </div>
    </div>
    <script>
        function closeModal() {
            document.getElementById('modalNotif').style.display = 'none';
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <?php endif; ?>

</body>
</html>
