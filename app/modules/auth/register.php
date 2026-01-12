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
    <title>Register - Sistem Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    
    <div class="glass-panel fade-in" style="width: 100%; max-width: 400px; padding: 40px; text-align: center;">
        <h2 style="margin-bottom: 20px; color: var(--accent-color);">Daftar Akun</h2>
        <p style="color: var(--text-secondary); margin-bottom: 30px;">Buat akun baru untuk masuk.</p>

        <?php if(isset($_GET['error'])): ?>
            <div style="background: rgba(255, 77, 77, 0.2); color: #ff4d4d; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

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
            <button type="submit" class="btn btn-primary w-100" style="justify-content: center;">REGISTER</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="login.php" style="color: var(--text-secondary); text-decoration: none;">Sudah punya akun? Login</a>
        </div>
    </div>

</body>
</html>
