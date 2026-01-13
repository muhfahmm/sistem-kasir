<?php
/**
 * Debug Script - Cek User di Database
 * Jalankan via browser: http://localhost/website sistem kasir/debug_users.php
 */

require_once 'app/config/koneksi.php';

echo "<h2>Debug: User Database</h2>";
echo "<style>
    body { font-family: Arial; padding: 20px; background: #1a1a1a; color: #fff; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #444; padding: 12px; text-align: left; }
    th { background: #2a2a2a; }
    tr:hover { background: #2a2a2a; }
    .valid { color: #0f0; }
    .invalid { color: #f00; }
</style>";

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<p class='invalid'>Error: " . mysqli_error($conn) . "</p>";
    exit;
}

echo "<table>";
echo "<tr>
        <th>ID</th>
        <th>Username</th>
        <th>Nama Lengkap</th>
        <th>Role</th>
        <th>Password (first 30 chars)</th>
        <th>Created At</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $password_preview = substr($row['password'], 0, 30) . '...';
    
    echo "<tr>";
    echo "<td>" . $row['id_user'] . "</td>";
    echo "<td><strong>" . $row['username'] . "</strong></td>";
    echo "<td>" . $row['nama_lengkap'] . "</td>";
    echo "<td><strong>" . $row['role'] . "</strong></td>";
    echo "<td><small>" . $password_preview . "</small></td>";
    echo "<td>" . $row['created_at'] . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Informasi Penting:</h3>";
echo "<ul>";
echo "<li>Jika kolom <strong>Role</strong> berisi 'admin' atau 'kasir' saja → User hanya punya 1 role</li>";
echo "<li>Jika kolom <strong>Role</strong> berisi 'admin,kasir' → User punya 2 role (multiple roles)</li>";
echo "<li>Saat login, pilih role yang <strong>sesuai</strong> dengan yang ada di database</li>";
echo "</ul>";

echo "<h3>Contoh:</h3>";
echo "<ul>";
echo "<li>User 'admin' dengan role 'admin' → Login sebagai: <strong>Admin</strong></li>";
echo "<li>User 'kasir' dengan role 'kasir' → Login sebagai: <strong>Kasir</strong></li>";
echo "<li>User 'superuser' dengan role 'admin,kasir' → Login sebagai: <strong>Admin</strong> atau <strong>Kasir</strong></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Test Password Hash:</h3>";

$test_password = '123';
$expected_hash = '$2y$10$uaWN9HczMifsB9TpsgRC1OirISeo1aQDWX9hjlSFD8xWefIpZGwRC';

echo "<p>Password yang ditest: <strong>$test_password</strong></p>";

$query_users = "SELECT username, password FROM users";
$result_users = mysqli_query($conn, $query_users);

echo "<table>";
echo "<tr><th>Username</th><th>Password Valid?</th></tr>";

while ($user = mysqli_fetch_assoc($result_users)) {
    $is_valid = password_verify($test_password, $user['password']);
    $status = $is_valid ? "<span class='valid'>✅ Valid</span>" : "<span class='invalid'>❌ Invalid</span>";
    
    echo "<tr>";
    echo "<td>" . $user['username'] . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>
