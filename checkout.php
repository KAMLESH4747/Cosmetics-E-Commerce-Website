<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Lumina | Premium Cosmetics</title>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles.css">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#1a1a1a',
                        'brand-rose': '#eeb4b4',
                        'brand-cream': '#f9f5f0',
                        'brand-accent': '#d4a373'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-brand-cream text-brand-dark antialiased">

    <!-- Navbar (Simplified for Checkout) -->
    <nav class="w-full px-6 py-6 border-b border-gray-200 bg-white/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.html" class="text-2xl font-serif font-bold tracking-tighter">Lumina.</a>
            <a href="index.html" class="text-sm font-medium hover:text-brand-rose transition-colors">Continue
                Shopping</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12 md:py-20">
        <h1 class="text-3xl md:text-5xl font-serif mb-12 text-center md:text-left">Checkout.</h1>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-24">
            <!-- Left Column: Shipping & Payment -->
            <div class="md:col-span-7 space-y-12">
                <!-- Shipping Details -->
                <section>
                    <h2 class="text-xl font-serif font-bold mb-6 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center text-sm">1</span>
                        Shipping Details
                    </h2>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-widest text-gray-500">First Name</label>
                                <input type="text" id="first-name"
                                    class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                    placeholder="Jane">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-widest text-gray-500">Last Name</label>
                                <input type="text" id="last-name"
                                    class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                    placeholder="Doe">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-widest text-gray-500">Email Address</label>
                            <input type="email"
                                class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                placeholder="jane@example.com">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-widest text-gray-500">Address</label>
                            <input type="text"
                                class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                placeholder="123 Beauty Lane">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-widest text-gray-500">City</label>
                                <input type="text"
                                    class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                    placeholder="New York">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-widest text-gray-500">Postal Code</label>
                                <input type="text"
                                    class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                    placeholder="10001">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-widest text-gray-500">Phone Number</label>
                            <input type="tel" id="payment-phone" required
                                class="w-full bg-transparent border-b border-brand-dark py-2 focus:outline-none focus:border-brand-rose transition-colors"
                                placeholder="+1 (555) 000-0000">
                        </div>
                    </form>
                </section>


                <!-- Payment Method -->
                <section>
                    <h2 class="text-xl font-serif font-bold mb-6 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center text-sm">2</span>
                        Payment Method
                    </h2>
                    <div class="p-6 border border-gray-200 rounded-sm bg-white/50">
                        <p class="text-sm text-gray-500 mb-4">Transactions are secure and encrypted.</p>

                        <div class="space-y-4">
                            <!-- Option A: Pay Online -->
                            <label
                                class="flex items-center gap-3 p-4 border border-brand-dark rounded-sm cursor-pointer transition-colors hover:bg-gray-50">
                                <input type="radio" name="payment-method" value="online" checked
                                    class="accent-brand-dark w-5 h-5">
                                <span class="font-medium flex-1">Pay Online (Credit / Debit / UPI)</span>
                                <div class="flex gap-2 text-gray-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z" />
                                    </svg>
                                </div>
                            </label>

                            <!-- Option B: Cash on Delivery -->
                            <label
                                class="flex items-center gap-3 p-4 border border-gray-200 rounded-sm cursor-pointer transition-colors hover:bg-gray-50">
                                <input type="radio" name="payment-method" value="cod" class="accent-brand-dark w-5 h-5">
                                <span class="font-medium flex-1">Cash on Delivery (COD)</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </label>
                        </div>
                    </div>

                    <button id="place-order-btn"
                        class="w-full mt-8 bg-brand-dark text-white py-4 text-sm uppercase tracking-[0.2em] font-bold hover:bg-brand-rose transition-colors duration-300 shadow-lg">
                        Place Order
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-4">By clicking "Place Order", you agree to our Terms
                        of
                        Service.</p>
                </section>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="md:col-span-5">
                <div
                    class="bg-white p-6 md:p-8 shadow-xl sticky top-24 transform rotate-1 hover:rotate-0 transition-transform duration-500 ease-out">
                    <h3 class="font-serif text-2xl mb-6 pb-4 border-b border-gray-100">Order Summary</h3>

                    <!-- Items Container -->
                    <div id="checkout-items" class="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <!-- Items will be injected here via JS -->
                        <p class="text-gray-500 text-sm text-center py-4">Loading cart...</p>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 pt-6 border-t border-gray-100">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span id="checkout-subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span>Calculated next step</span>
                        </div>
                        <div class="flex justify-between text-lg font-medium pt-4 border-t border-gray-100 mt-4">
                            <span>Total</span>
                            <span id="checkout-total">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Custom Script -->
    <script src="script.js"></script>
    <script>
        // Inline init for page-specific setup if script.js relies on it, 
        // OR we can move the init call to script.js wrapped in a check.
        // For now, let's keep only Razorpay SDK and our script.js
    </script>
</body>

</html>