<?php
require_once 'auth_check.php';
require_once '../config.php';

// Handle Order Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $new_status, 'id' => $order_id]);
        $success_msg = "Order status updated successfully.";
    } catch (PDOException $e) {
        $error_msg = "Error updating status: " . $e->getMessage();
    }
}

// Fetch Orders
try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching orders: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Beauty Bazaar Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex flex-col md:flex-row">
        <!-- Sidebar -->
        <div
            class="bg-gray-900 text-white shadow-xl h-screen fixed top-0 left-0 bottom-0 overflow-y-auto w-64 z-10 hidden md:block">
            <div class="p-6">
                <a href="index.php" class="text-white text-2xl font-semibold uppercase hover:text-gray-300">Admin
                    Panel</a>
            </div>
            <nav class="mt-4">
                <a href="index.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="orders.php"
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-800 text-white border-l-4 border-blue-500">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="logout.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white mt-auto">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Mobile Header -->
        <div class="md:hidden bg-gray-900 text-white p-4 flex justify-between items-center w-full z-10">
            <span class="font-semibold text-xl">Admin Panel</span>
            <button id="mobile-menu-btn" class="text-white focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Sidebar (Overlay) -->
        <div id="mobile-sidebar"
            class="bg-gray-900 text-white shadow-xl h-screen fixed top-0 left-0 bottom-0 overflow-y-auto w-64 z-20 transform -translate-x-full transition-transform duration-300 md:hidden">
            <div class="p-6 flex justify-between items-center">
                <a href="index.php" class="text-white text-2xl font-semibold uppercase hover:text-gray-300">Admin
                    Panel</a>
                <button id="close-mobile-menu" class="text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="mt-4">
                <a href="index.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="orders.php"
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-800 text-white border-l-4 border-blue-500">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="logout.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white mt-auto">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-full md:pl-64">
            <div class="p-8">
                <h1 class="text-3xl text-gray-800 font-bold mb-6">Order Management</h1>

                <?php if (isset($success_msg)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">
                            <?php echo $success_msg; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_msg)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">
                            <?php echo $error_msg; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">Order ID</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Customer Name</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Date</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Total</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if ($orders): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="w-1/12 py-3 px-4 border-b border-gray-200 text-center">
                                            <?php echo $order['id']; ?>
                                        </td>
                                        <td class="w-1/4 py-3 px-4 border-b border-gray-200">
                                            <?php echo htmlspecialchars($order['customer_name']); ?>
                                        </td>
                                        <td class="w-1/4 py-3 px-4 border-b border-gray-200 text-center">
                                            <?php echo date("F j, Y, g:i a", strtotime($order['created_at'])); ?>

                                            <!-- Items Display -->
                                            <?php
                                            $items = json_decode($order['items'], true);
                                            if ($items) {
                                                echo '<div class="mt-1 text-xs text-gray-500">';
                                                foreach ($items as $item) {
                                                    echo '<div>' . htmlspecialchars($item['name']) . ' x ' . $item['quantity'] . '</div>';
                                                }
                                                echo '</div>';
                                            }
                                            ?>
                                        </td>
                                        <td class="w-1/6 py-3 px-4 border-b border-gray-200 text-center">$
                                            <?php echo number_format($order['total_amount'], 2); ?>
                                        </td>
                                        <td class="w-1/4 py-3 px-4 border-b border-gray-200 text-center">
                                            <form method="POST">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <div class="inline-block relative w-40">
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                                        <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">No orders found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileSidebar.classList.remove('-translate-x-full');
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileSidebar.classList.add('-translate-x-full');
        });
    </script>
</body>

</html>