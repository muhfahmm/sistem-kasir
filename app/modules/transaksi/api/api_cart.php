<?php
session_start();
include '../../../config/koneksi.php';

// Inisialisasi Keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_GET['act']) ? $_GET['act'] : '';

// --------------------------------------------------------------------------
// 1. ADD ITEM BY ID (Klik Manual)
// --------------------------------------------------------------------------
if ($action == 'add') {
    $id = $_GET['id'];
    $qty = 1;

    // Cek stok dulu
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
    $produk = mysqli_fetch_assoc($query);

    if ($produk && $produk['stok'] > 0) {
        // Jika sudah ada di cart, tambah qty
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
        } else {
            // Jika belum, masukkan baru
            $_SESSION['cart'][$id] = [
                'id' => $produk['id_produk'],
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga_jual'],
                'qty' => $qty
            ];
        }
        echo json_encode(['status' => 'success', 'message' => 'Produk ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Stok habis atau produk tidak ditemukan']);
    }
}

// --------------------------------------------------------------------------
// 2. ADD ITEM BY BARCODE (Scanner)
// --------------------------------------------------------------------------
elseif ($action == 'add_by_code') {
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE kode_produk = '$code'");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        $id = $produk['id_produk'];
        if ($produk['stok'] > 0) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] += 1;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $produk['id_produk'],
                    'nama' => $produk['nama_produk'],
                    'harga' => $produk['harga_jual'],
                    'qty' => 1
                ];
            }
            echo json_encode(['status' => 'success', 'product_name' => $produk['nama_produk']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Stok Habis!']);
        }
    } else {
        // Return status not_found agar frontend bisa redirect ke form tambah produk
        echo json_encode(['status' => 'not_found', 'code' => $code]);
    }
}

// --------------------------------------------------------------------------
// 3. VIEW CART (HTML Render)
// --------------------------------------------------------------------------
elseif ($action == 'view') {
    if (empty($_SESSION['cart'])) {
        echo '<div style="text-align: center; color: var(--text-secondary); margin-top: 50px;">
                <i class="fas fa-basket-shopping fa-3x" style="opacity: 0.3;"></i>
                <p style="margin-top: 10px;">Keranjang Masih Kosong</p>
              </div>';
    } else {
        foreach ($_SESSION['cart'] as $id => $item) {
            $subtotal = $item['harga'] * $item['qty'];
            ?>
            <div class="glass-panel" style="padding: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <h5 style="margin-bottom: 4px;"><?= $item['nama'] ?></h5>
                    <small style="color: var(--text-secondary);">
                        Rp <?= number_format($item['harga']) ?> x <?= $item['qty'] ?>
                    </small>
                </div>
                <div style="text-align: right;">
                    <span style="display: block; font-weight: bold; margin-bottom: 5px;">
                        Rp <?= number_format($subtotal) ?>
                    </span>
                    <button class="btn btn-danger" onclick="removeFromCart(<?= $id ?>)" style="padding: 2px 8px; font-size: 10px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <?php
        }
    }
}

// --------------------------------------------------------------------------
// 4. GET TOTAL (JSON)
// --------------------------------------------------------------------------
elseif ($action == 'total') {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['harga'] * $item['qty']);
    }
    echo json_encode(['total' => $total, 'total_formatted' => number_format($total)]);
}

// --------------------------------------------------------------------------
// 5. REMOVE ITEM
// --------------------------------------------------------------------------
elseif ($action == 'remove') {
    $id = $_GET['id'];
    unset($_SESSION['cart'][$id]);
    echo json_encode(['status' => 'success']);
}

// --------------------------------------------------------------------------
// 6. RESET CART
// --------------------------------------------------------------------------
elseif ($action == 'reset') {
    $_SESSION['cart'] = [];
    echo json_encode(['status' => 'success']);
}
?>
