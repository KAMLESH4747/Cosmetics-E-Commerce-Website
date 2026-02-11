<?php
require_once 'auth_check.php';
require_once '../config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;

    // Image Upload Logic
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../uploads/';
        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;

        // Allow certain file formats
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image, is_bestseller) VALUES (:name, :description, :price, :category, :image, :is_bestseller)");
                    $stmt->execute([
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'category' => $category,
                        'image' => $file_name,
                        'is_bestseller' => $is_bestseller
                    ]);
                    $success = "Product added successfully!";
                } catch (PDOException $e) {
                    $error = "Error adding product to database: " . $e->getMessage();
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "Sorry, only JPG, JPEG, PNG, GIF, & WEBP files are allowed.";
        }
    } else {
        $error = "Please select an image for the product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Beauty Bazaar Admin</title>
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
                    <h1 class="text-3xl text-gray-800 font-bold">Add New Product</h1>
                    <a href="products.php" class="text-blue-500 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Products
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">
                            <?php echo $error; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">
                            <?php echo $success; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <form action="add_product.php" method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Product Name
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="name" name="name" type="text" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                                    Price ($)
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="price" name="price" type="number" step="0.01" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                                    Category
                                </label>
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Skincare">Skincare</option>
                                    <option value="Makeup">Makeup</option>
                                    <option value="Haircare">Haircare</option>
                                    <option value="Fragrance">Fragrance</option>
                                    <option value="Bath & Body">Bath & Body</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                                    Product Image
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="image" name="image" type="file" accept="image/*" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                Description
                            </label>
                            <textarea
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_bestseller" class="form-checkbox h-5 w-5 text-blue-600">
                                <span class="ml-2 text-gray-700">Mark as Bestseller</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                                type="submit">
                                Add Product
                            </button>
                        </div>
                    </form>
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