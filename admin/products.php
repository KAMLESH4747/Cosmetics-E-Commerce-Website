<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'auth_check.php';
require_once '../config.php';

// Handle Product Deletion
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    try {
        // Get image path to delete file
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = :id");
        $stmt->execute(['id' => $product_id]);
        $product = $stmt->fetch();

        if ($product) {
            $image_path = '../uploads/' . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute(['id' => $product_id]);
            $success_msg = "Product deleted successfully.";
        }
    } catch (PDOException $e) {
        $error_msg = "Error deleting product: " . $e->getMessage();
    }
}

// Fetch Products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching products: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Beauty Bazaar Admin</title>
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
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-800 text-white border-l-4 border-blue-500">
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
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-800 text-white border-l-4 border-blue-500">
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
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl text-gray-800 font-bold">Product Management</h1>
                    <a href="add_product.php"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                    </a>
                </div>

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
                    <table class="min-w-full bg-white grid-cols-1">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Image</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Name</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Category</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Price</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if ($products): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td class="w-1/12 py-3 px-4 border-b border-gray-200 text-center">
                                            <?php echo $product['id']; ?>
                                        </td>
                                        <td class="w-1/6 py-3 px-4 border-b border-gray-200 text-center">
                                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                                alt="Product Image" class="h-16 w-16 object-cover mx-auto rounded">
                                        </td>
                                        <td class="w-1/4 py-3 px-4 border-b border-gray-200">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </td>
                                        <td class="w-1/6 py-3 px-4 border-b border-gray-200 text-center">
                                            <?php echo htmlspecialchars($product['category']); ?>
                                        </td>
                                        <td class="w-1/6 py-3 px-4 border-b border-gray-200 text-center">$
                                            <?php echo number_format($product['price'], 2); ?>
                                        </td>
                                        <td class="w-1/6 py-3 px-4 border-b border-gray-200 text-center">
                                            <form method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" name="delete_product"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500">No products found.</td>
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