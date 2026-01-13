<?php
/**
 * Script untuk Generate Password Hash
 * Gunakan script ini untuk membuat password hash yang benar
 */

// Password yang ingin di-hash
$password = '123';

// Generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n\n";

echo "Copy hash di atas dan gunakan untuk update database:\n";
echo "UPDATE users SET password = '$hash' WHERE username = 'admin';\n";
echo "UPDATE users SET password = '$hash' WHERE username = 'kasir';\n";
echo "UPDATE users SET password = '$hash' WHERE username = 'superuser';\n";
?>
