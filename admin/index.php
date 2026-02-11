<?php
require_once 'auth_check.php';
require_once '../config.php';

// Fetch Statistics
try {
    // Total Products
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_products = $stmt->fetchColumn();

    // Total Orders
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $total_orders = $stmt->fetchColumn();

    // Pending Orders
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
    $pending_orders = $stmt->fetchColumn();

    // Total Revenue
    $stmt = $pdo->query("SELECT SUM(total_amount) FROM orders");
    $total_revenue = $stmt->fetchColumn();
    $total_revenue = $total_revenue ? $total_revenue : 0; // Handle null if no orders

} catch (PDOException $e) {
    $error = "Error fetching stats: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Beauty Bazaar</title>
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
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-800 text-white border-l-4 border-blue-500">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="orders.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
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
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-800 text-white border-l-4 border-blue-500">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="orders.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
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
                <h1 class="text-3xl text-gray-800 font-bold mb-6">Dashboard Overview</h1>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">
                            <?php echo $error; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Products
                                </p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    <?php echo $total_products; ?>
                                </h3>
                            </div>
                            <div class="text-blue-500 text-4xl">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Orders</p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    <?php echo $total_orders; ?>
                                </h3>
                            </div>
                            <div class="text-green-500 text-4xl">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Pending Orders
                                </p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    <?php echo $pending_orders; ?>
                                </h3>
                            </div>
                            <div class="text-yellow-500 text-4xl">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 4 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Revenue</p>
                                <h3 class="text-3xl font-bold text-gray-800">$
                                    <?php echo number_format($total_revenue, 2); ?>
                                </h3>
                            </div>
                            <div class="text-purple-500 text-4xl">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="p-4 text-center text-gray-600 mt-8">
                &copy;
                <?php echo date("Y"); ?> Beauty Bazaar Admin.
            </footer>
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