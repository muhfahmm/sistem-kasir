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
                'harga' => $produk['harga'],
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
                    'harga' => $produk['harga'],
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
// --------------------------------------------------------------------------
// 7. UPDATE QUANTITY (+ / -)
// --------------------------------------------------------------------------
elseif ($action == 'update_qty') {
    $id = $_GET['id'];
    $change = (int)$_GET['change']; // +1 atau -1
    
    if (isset($_SESSION['cart'][$id])) {
        $newQty = $_SESSION['cart'][$id]['qty'] + $change;
        
        // Cek Stok di DB (Optional but recommended)
        // Untuk kecepatan, sementara kita asumsikan stok cukup untuk penambahan kecil
        // Idealnya query DB lagi.
        
        if ($newQty < 1) {
            // Jika jadi < 1, jangan ubah (biar user pakai tombol hapus)
            // Atau bisa auto hapus. Kita pilih minimum 1.
            $newQty = 1;
        }
        
        $_SESSION['cart'][$id]['qty'] = $newQty;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
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
            <div class="glass-panel" style="padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-start; background: rgba(255,255,255,0.05);">
                <div style="flex: 1; padding-right: 10px;">
                    <h5 style="margin: 0 0 5px; font-size: 0.95rem; color: #eee;"><?= $item['nama'] ?></h5>
                    <small style="color: var(--text-secondary); display: block; margin-bottom: 5px;">
                        @ Rp <?= number_format($item['harga']) ?>
                    </small>
                </div>
                
                <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end;">
                    <span style="display: block; font-weight: bold; margin-bottom: 8px; color: var(--accent-color); font-size: 0.95rem;">
                        Rp <?= number_format($subtotal) ?>
                    </span>
                    
                    <div style="display: flex; align-items: center; gap: 5px; background: rgba(0,0,0,0.3); padding: 4px; border-radius: 8px;">
                        <button onclick="updateQty('<?= $id ?>', -1)" class="btn-qty" style="width: 28px; height: 28px; border-radius: 6px; border: none; background: rgba(255,255,255,0.1); color: white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-minus" style="font-size: 10px;"></i>
                        </button>
                        
                        <span style="min-width: 25px; text-align: center; font-weight: bold; font-size: 0.9rem; color: white;">
                            <?= $item['qty'] ?>
                        </span>
                        
                        <button onclick="updateQty('<?= $id ?>', 1)" class="btn-qty" style="width: 28px; height: 28px; border-radius: 6px; border: none; background: var(--accent-color); color: black; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-plus" style="font-size: 10px;"></i>
                        </button>
                        
                        <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.2); margin: 0 5px;"></div>
                        
                        <button onclick="removeFromCart('<?= $id ?>')" style="color: #ff453a; background: none; border: none; cursor: pointer; padding: 0 5px;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
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
