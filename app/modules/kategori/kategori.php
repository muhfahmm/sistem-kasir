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
                    <!-- View Mode -->
                    <span id="view_nama_<?= $d['id_kategori'] ?>"><?= $d['nama_kategori'] ?></span>
                    
                    <!-- Edit Mode (Hidden by default) -->
                    <form id="form_edit_<?= $d['id_kategori'] ?>" action="api/proses.php" method="POST" style="display:none;" class="d-flex gap-2">
                        <input type="hidden" name="id_kategori" value="<?= $d['id_kategori'] ?>">
                        <input type="text" name="nama_kategori" value="<?= $d['nama_kategori'] ?>" class="form-control" style="padding: 2px 8px; height: 30px;" required>
                        <button type="submit" name="edit" class="btn btn-success" style="padding: 2px 8px; height: 30px;"><i class="fas fa-check"></i></button>
                        <button type="button" onclick="cancelEdit(<?= $d['id_kategori'] ?>)" class="btn btn-secondary" style="padding: 2px 8px; height: 30px;"><i class="fas fa-times"></i></button>
                    </form>
                </td>
                <td>
                    <div id="action_btn_<?= $d['id_kategori'] ?>">
                        <button onclick="editKategori(<?= $d['id_kategori'] ?>)" class="btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; font-size: 12px;"><i class="fas fa-edit" style="color: var(--warning-color);"></i></button>
                        
                        <form action="api/proses.php" method="POST" onsubmit="return confirm('Hapus kategori ini?');" style="display:inline;">
                            <input type="hidden" name="id_kategori" value="<?= $d['id_kategori'] ?>">
                            <button type="submit" name="delete" class="btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; font-size: 12px;"><i class="fas fa-trash" style="color: var(--danger-color);"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<script>
function editKategori(id) {
    // Hide View elements
    document.getElementById('view_nama_' + id).style.display = 'none';
    document.getElementById('action_btn_' + id).style.display = 'none';
    
    // Show Edit Form
    document.getElementById('form_edit_' + id).style.display = 'flex';
}

function cancelEdit(id) {
    // Show View elements
    document.getElementById('view_nama_' + id).style.display = 'inline';
    document.getElementById('action_btn_' + id).style.display = 'block';
    
    // Hide Edit Form
    document.getElementById('form_edit_' + id).style.display = 'none';
}
</script>
</div>

<?php include '../../template/footer.php'; ?>
