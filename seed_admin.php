<?php
require_once 'config.php';

$username = 'admin';
$password = 'password123';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin user already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetchColumn() > 0) {
        echo "Admin user already exists.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (:username, :password_hash)");
        $stmt->execute(['username' => $username, 'password_hash' => $password_hash]);
        echo "Admin user created successfully. Username: $username, Password: $password";
    }
} catch (PDOException $e) {
    echo "Error creating admin user: " . $e->getMessage();
}
?>