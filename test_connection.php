<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

try {
    // Check connection is available (pdo is created in config.php)
    if ($pdo) {
        echo "✅ Database Connected Successfully.<br>";

        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        $count = $stmt->fetchColumn();

        echo "✅ Found [$count] products in the table.<br>";

        if ($count == 0) {
            echo "⚠️ The table is empty. You need to add a product first.<br>";
        }
    }
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>