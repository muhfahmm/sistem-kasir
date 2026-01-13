<?php
include '../../config/koneksi.php';
require_once '../../config/auth_check.php'; // Cek Sesi Login Logic

$id = isset($_GET['id']) ? $_GET['id'] : '';
$is_edit = $id != '';

$data = [
    'kode_produk' => '', 'nama_produk' => '', 'id_kategori' => '', 'harga' => '', 'stok' => ''
];

// Cek jika ada parameter code dari hasil scan
if(isset($_GET['code'])) {
    $data['kode_produk'] = $_GET['code'];
}

if ($is_edit) {
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
    $data = mysqli_fetch_assoc($result);
}

include '../../template/header.php';
include '../../template/sidebar.php';
?>

<div class="glass-panel fade-in" style="max-width: 800px; margin: 0 auto;">
    <h3 style="margin-bottom: 20px;"><?= $is_edit ? 'Edit' : 'Tambah' ?> Produk</h3>
    
    <form action="api/proses.php" method="POST">
        <?php if($is_edit): ?>
            <input type="hidden" name="id_produk" value="<?= $id ?>">
        <?php endif; ?>

        <div class="d-flex gap-2" style="margin-bottom: 15px;">
            <div class="w-100">
                <label style="display:block; margin-bottom:5px;">Kode Produk</label>
                <input type="text" name="kode_produk" class="form-control" value="<?= $data['kode_produk'] ?>" required>
            </div>
            <div class="w-100">
                <label style="display:block; margin-bottom:5px;">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" value="<?= $data['nama_produk'] ?>" required>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Kategori</label>
            <div class="d-flex gap-2">
                <select name="id_kategori" id="selectKategori" class="form-control">
                    <?php
                    $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
                    while($k = mysqli_fetch_assoc($kat)):
                        $selected = ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                    ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= $selected ?>><?= $k['nama_kategori'] ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="button" class="btn btn-primary" onclick="addKategoriBaru()" style="white-space: nowrap;"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <script>
        function addKategoriBaru() {
            const nama = prompt("Masukkan Nama Kategori Baru:");
            if (nama) {
                // Kirim AJAX ke API Kategori
                const formData = new FormData();
                formData.append('nama_kategori', nama);

                fetch('../kategori/api/api_ajax_add.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Tambahkan option baru ke select dan pilih otomatis
                        const select = document.getElementById('selectKategori');
                        const option = new Option(data.nama, data.id, true, true);
                        select.add(option, 0); // Tambah di paling atas
                        alert("Kategori '" + data.nama + "' berhasil ditambahkan!");
                    } else {
                        alert("Gagal: " + data.message);
                    }
                })
                .catch(err => console.error(err));
            }
        }
        </script>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" required placeholder="Harga jual produk">
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display:block; margin-bottom:5px;">Stok Awal</label>
            <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>" required>
        </div>

        <button type="submit" name="<?= $is_edit ? 'update' : 'simpan' ?>" class="btn btn-primary w-100" style="justify-content:center;">Simpan Data</button>
        <a href="index.php" class="btn w-100" style="margin-top: 10px; background: rgba(255,255,255,0.1); color: #fff; justify-content:center;">Batal</a>
    </form>
</div>

<?php include '../../template/footer.php'; ?>
