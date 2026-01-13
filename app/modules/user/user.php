<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic
include '../../template/header.php';
include '../../template/sidebar.php';
?>

<div class="glass-panel fade-in">
    <h3>Data Pengguna System</h3>
    <br>
    <table class="table-glass">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Role</th>
                <th>Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data = mysqli_query($conn, "SELECT * FROM users");
            while($u = mysqli_fetch_assoc($data)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $u['username'] ?></td>
                <td><?= $u['nama_lengkap'] ?></td>
                <td>
                    <?php if($u['role'] == 'admin'): ?>
                        <span style="background: rgba(0, 212, 255, 0.2); color: var(--accent-color); padding: 5px 10px; border-radius: 6px;">ADMIN</span>
                    <?php else: ?>
                         <span style="background: rgba(255, 255, 255, 0.1); padding: 5px 10px; border-radius: 6px;">KASIR</span>
                    <?php endif; ?>
                </td>
                <td><?= substr($u['created_at'], 0, 10) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../template/footer.php'; ?>
