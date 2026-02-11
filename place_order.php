<?php
require_once 'config.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

// Validate required fields
if (empty($data['customer_name']) || empty($data['items']) || !isset($data['total_amount'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$customer_name = $data['customer_name'];
$total_amount = $data['total_amount'];
$items = json_encode($data['items']); // Store items as JSON string

try {
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, total_amount, items, status) VALUES (:customer_name, :total_amount, :items, 'pending')");
    $stmt->execute([
        'customer_name' => $customer_name,
        'total_amount' => $total_amount,
        'items' => $items
    ]);

    $orderId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>