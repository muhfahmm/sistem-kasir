<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic

include '../../template/header.php';
include '../../template/sidebar.php';
?>

<div class="glass-panel fade-in">
    <div class="d-flex justify-between align-center" style="margin-bottom: 20px;">
        <h3>Data Kategori</h3>
        <!-- Form Tambah Inline -->
        <form action="api/proses.php" method="POST" class="d-flex gap-2">
            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori Baru" required>
            <button type="submit" name="add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</button>
        </form>
    </div>

    <table class="table-glass">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Kategori</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
            while($d = mysqli_fetch_assoc($data)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <!-- Simple Edit Form by Value Replacement could be done via JS, here we just show text -->
                    <?= $d['nama_kategori'] ?>
                </td>
                <td>
                    <form action="api/proses.php" method="POST" onsubmit="return confirm('Hapus kategori ini?');" style="display:inline;">
                        <input type="hidden" name="id_kategori" value="<?= $d['id_kategori'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../template/footer.php'; ?>
