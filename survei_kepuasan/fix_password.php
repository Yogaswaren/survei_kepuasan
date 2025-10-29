<?php
require_once 'config/database.php';

$username = 'admin';
$password = 'admin123';
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
$result = $stmt->execute([$hashed, $username]);

if ($result) {
    echo "✓ Password berhasil diupdate!<br><br>";
    echo "<strong>Login dengan:</strong><br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br><br>";
    echo "Hash baru: " . $hashed . "<br><br>";
    echo '<a href="admin/login.php">Kembali ke Login</a>';
} else {
    echo "× Gagal update password";
}
?>