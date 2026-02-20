// Initialize GSAP
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    initHeroAnimations();
    initNavbarScroll();
    initScrollAnimations();
    initCart();
    initProductModal();
    initSearch();
    initCategoriesDropdown();
    initCategorySectionLinks();
    initURLParams();
    loadCart(); // Load cart data on startup
    initCheckoutPage(); // Initialize checkout page logic if present
});

// --- Cart System ---
const cartState = {
    items: [],
    isOpen: false
};

// Load Cart from LocalStorage
function loadCart() {
    const savedCart = localStorage.getItem('cartItems');
    if (savedCart) {
        cartState.items = JSON.parse(savedCart);
        updateCartUI();
    }
}

// Save Cart to LocalStorage
function saveCart() {
    localStorage.setItem('cartItems', JSON.stringify(cartState.items));
}

function initCart() {
    const cartBtn = document.getElementById('cart-btn');
    const closeCartBtn = document.getElementById('close-cart');
    const overlay = document.getElementById('overlay');
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    const modalAddToCartBtn = document.getElementById('modal-add-to-cart');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Checkout Button
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', handlePayment);
    }

    // Toggle Cart
    cartBtn.addEventListener('click', toggleCart);
    closeCartBtn.addEventListener('click', toggleCart);
    overlay.addEventListener('click', () => {
        if (cartState.isOpen) toggleCart();
        closeModal();
    });

    // Add to Cart Logic for Grid Items
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent opening modal if clicking button
            const card = e.target.closest('.product-card');
            const product = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                image: card.dataset.image,
                quantity: 1
            };
            addToCart(product);
        });
    });

    // Modal Add to Cart
    if (modalAddToCartBtn) {
        modalAddToCartBtn.addEventListener('click', () => {
            // Retrieve current product from modal dataset (we'll set this when opening modal)
            const modal = document.getElementById('product-modal');
            const product = {
                id: modal.dataset.id,
                name: modal.dataset.name,
                price: parseFloat(modal.dataset.price),
                image: modal.dataset.image,
                quantity: 1
            };
            addToCart(product);
            closeModal();
            toggleCart(); // Open cart after adding
        });
    }
}

function toggleCart() {
    const cartSidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('overlay');

    cartState.isOpen = !cartState.isOpen;

    if (cartState.isOpen) {
        cartSidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        // small delay to allow display:block to apply before opacity transition
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    } else {
        cartSidebar.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }
}

function addToCart(productToAdd) {
    const existingItem = cartState.items.find(item => item.id === productToAdd.id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cartState.items.push(productToAdd);
    }

    updateCartUI();
}

function removeFromCart(productId) {
    cartState.items = cartState.items.filter(item => item.id !== productId);
    updateCartUI();
}

function updateCartItemQuantity(productId, change) {
    const item = cartState.items.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            updateCartUI();
        }
    }
}

function updateCartUI() {
    const cartCount = document.getElementById('cart-count');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    // Guard: these elements only exist on index.php, not checkout.php
    if (!cartCount || !cartItemsContainer || !cartTotal) return;

    // Update Count
    const totalItems = cartState.items.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.innerText = totalItems;

    // Render Items
    if (cartState.items.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-center text-gray-500">
                <p class="mb-4">Your bag is empty.</p>
                <button onclick="toggleCart()" class="text-brand-dark border-b border-brand-dark pb-1 text-sm uppercase tracking-widest hover:text-brand-rose hover:border-brand-rose transition-colors">Start Shopping</button>
            </div>
        `;
        cartTotal.innerText = '$0.00';
    } else {
        cartItemsContainer.innerHTML = cartState.items.map(item => `
            <div class="flex gap-4">
                <div class="w-20 h-24 rounded-sm flex-shrink-0 bg-stone-200 overflow-hidden">
                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-serif text-lg leading-tight mb-1">${item.name}</h4>
                        <p class="text-sm text-gray-500">$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center border border-gray-200">
                            <button onclick="updateCartItemQuantity('${item.id}', -1)" class="w-6 h-6 flex items-center justify-center hover:bg-gray-100">-</button>
                            <span class="w-8 text-center text-xs">${item.quantity}</span>
                            <button onclick="updateCartItemQuantity('${item.id}', 1)" class="w-6 h-6 flex items-center justify-center hover:bg-gray-100">+</button>
                        </div>
                        <button onclick="removeFromCart('${item.id}')" class="text-xs text-gray-400 border-b border-transparent hover:border-gray-400 hover:text-brand-dark transition-all">Remove</button>
                    </div>
                </div>
            </div>
        `).join('');

        // Update Total
        const total = cartState.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotal.innerText = '$' + total.toFixed(2);
    }
    saveCart(); // Save state whenever UI updates
}

// Global functions for inline onclick handlers
window.updateCartItemQuantity = updateCartItemQuantity;
window.removeFromCart = removeFromCart;
window.toggleCart = toggleCart;


// --- Product Modal System ---
function initProductModal() {
    const modal = document.getElementById('product-modal');
    const closeBtn = document.getElementById('close-modal');
    const overlay = document.getElementById('overlay');
    const viewButtons = document.querySelectorAll('.view-product');

    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.product-card');
            const product = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: card.dataset.price,
                description: card.dataset.description,
                category: card.dataset.category,
                image: card.dataset.image
            };
            openModal(product);
        });
    });

    closeBtn.addEventListener('click', closeModal);
}

function openModal(product) {
    const modal = document.getElementById('product-modal');
    const overlay = document.getElementById('overlay');

    // Populate Data
    document.getElementById('modal-category').innerText = product.category;
    document.getElementById('modal-title').innerText = product.name;
    document.getElementById('modal-price').innerText = '$' + product.price;
    document.getElementById('modal-description').innerText = product.description;

    const modalImageContainer = document.getElementById('modal-image');
    modalImageContainer.innerHTML = `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">`;

    // Store data on modal for "Add to Cart"
    modal.dataset.image = product.image;
    modal.dataset.id = product.id;
    modal.dataset.name = product.name;
    modal.dataset.price = product.price;

    modal.classList.remove('hidden');
    modal.classList.add('flex'); // Flex to center
    overlay.classList.remove('hidden');
    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('product-modal');
    const overlay = document.getElementById('overlay');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    // Only hide overlay if cart isn't open
    if (!cartState.isOpen) {
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }
}

function initHeroAnimations() {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.to('.hero-elem', {
        y: 0,
        opacity: 1,
        duration: 1,
        stagger: 0.2,
        delay: 0.2
    });
}

function initNavbarScroll() {
    const navbar = document.getElementById('navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('glass-nav');
        } else {
            navbar.classList.remove('glass-nav');
        }
    });
}

function initScrollAnimations() {
    // Animate Section Headers
    gsap.utils.toArray('section h2').forEach(header => {
        gsap.from(header, {
            y: 30,
            opacity: 0,
            duration: 1,
            scrollTrigger: {
                trigger: header,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            }
        });
    });

    // Stagger Product Grids
    gsap.utils.toArray('.grid').forEach(grid => {
        gsap.from(grid.children, {
            y: 50,
            opacity: 0,
            duration: 0.8,
            stagger: 0.1,
            scrollTrigger: {
                trigger: grid,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });
    });
}

// --- Categories Dropdown System ---
function initCategoriesDropdown() {
    const btn = document.getElementById('categories-btn');
    const dropdown = document.getElementById('categories-dropdown');

    if (!btn || !dropdown) return;

    // Toggle Dropdown
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown();
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
            closeDropdown();
        }
    });

    // Category Filter Logic
    const links = dropdown.querySelectorAll('a[data-category]');
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const category = link.dataset.category;
            selectCategory(category);
            closeDropdown();
        });
    });

    function toggleDropdown() {
        const isClosed = dropdown.classList.contains('invisible');
        if (isClosed) {
            dropdown.classList.remove('invisible', 'opacity-0', 'translate-y-2');
            btn.querySelector('svg').style.transform = 'rotate(180deg)';
        } else {
            closeDropdown();
        }
    }

    function closeDropdown() {
        dropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
        btn.querySelector('svg').style.transform = 'rotate(0deg)';
    }
}

// --- Search and Filter System ---
function initSearch() {
    const searchBtn = document.getElementById('search-btn');
    const closeSearchBtn = document.getElementById('close-search');
    const searchOverlay = document.getElementById('search-overlay');
    const searchInput = document.getElementById('search-input');
    const overlay = document.getElementById('overlay');

    // Toggle Search Logic
    function toggleSearch() {
        const isClosed = searchOverlay.classList.contains('-translate-y-full');

        if (isClosed) {
            searchOverlay.classList.remove('-translate-y-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            searchInput.focus();
            document.body.style.overflow = 'hidden';

            // Reset filter when opening search
            // filterProducts(''); 
        } else {
            searchOverlay.classList.add('-translate-y-full');
            if (!cartState.isOpen) {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }
        }
    }

    searchBtn.addEventListener('click', toggleSearch);
    closeSearchBtn.addEventListener('click', toggleSearch);

    // Live Filter Logic
    searchInput.addEventListener('keyup', (e) => {
        const term = e.target.value;
        filterProducts(term);

        const bestSellersSection = document.getElementById('bestsellers');
        if (term.length > 0) {
            bestSellersSection.scrollIntoView({ behavior: 'smooth' });
        }
    });
}

// Shared filter function
function filterProducts(term) {
    term = term.toLowerCase();
    const products = document.querySelectorAll('.product-card');

    products.forEach(product => {
        const name = product.dataset.name.toLowerCase();
        const category = product.dataset.category.toLowerCase();
        const description = product.dataset.description.toLowerCase();

        if (name.includes(term) || category.includes(term) || description.includes(term) || term === '') {
            product.style.display = 'block';
            gsap.to(product, { opacity: 1, y: 0, duration: 0.3 });
        } else {
            product.style.display = 'none';
        }
    });
}

// --- Payment System ---
function handlePayment() {
    if (cartState.items.length === 0) {
        alert("Your cart is empty!");
        return;
    }

    // Redirect to Checkout Page
    window.location.href = 'checkout.php';
}

// --- Category Section Links System ---
function initCategorySectionLinks() {
    const categoryLinks = document.querySelectorAll('.category-card-link');

    if (!categoryLinks.length) return;

    categoryLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // Prevent default anchor behavior if clicking the "View Collection" button
            const anchor = e.target.closest('a');
            if (anchor) {
                e.preventDefault();
            }

            const category = link.dataset.category;
            selectCategory(category);
        });
    });
}

// --- Unified Category Navigation Logic ---
function selectCategory(category) {
    if (!category) return;

    // 1. Update URL (Deep Linking)
    const url = new URL(window.location);
    url.searchParams.set('category', category);
    window.history.pushState({ category: category }, '', url);

    // 2. Scroll to Section
    // Normalize category to ID (e.g., "Skincare" -> "skincare")
    const sectionId = category.toLowerCase().replace(/\s+/g, '');
    const section = document.getElementById(sectionId);

    if (section) {
        // Offset for fixed navbar
        const yOffset = -100;
        const y = section.getBoundingClientRect().top + window.pageYOffset + yOffset;
        window.scrollTo({ top: y, behavior: 'smooth' });
    }
}

// Handle Browser Back/Forward
window.addEventListener('popstate', (e) => {
    const category = e.state ? e.state.category : '';
    if (category) {
        selectCategory(category);
    }
});

// Handle Initial Page Load
function initURLParams() {
    const params = new URLSearchParams(window.location.search);
    const category = params.get('category');
    if (category) {
        // Use a small timeout to ensure DOM elements are ready
        setTimeout(() => {
            selectCategory(category);
        }, 500);
    }
}


// --- Checkout Page Logic ---
function initCheckoutPage() {
    const checkoutItemsContainer = document.getElementById('checkout-items');
    const subtotalEl = document.getElementById('checkout-subtotal');
    const totalEl = document.getElementById('checkout-total');
    const placeOrderBtn = document.getElementById('place-order-btn');

    if (!placeOrderBtn) return; // Not on checkout page

    // Load cart data from localStorage directly (avoids crashing updateCartUI on checkout page)
    const savedCart = localStorage.getItem('cartItems');
    if (savedCart) {
        cartState.items = JSON.parse(savedCart);
    }

    if (cartState.items.length === 0) {
        checkoutItemsContainer.innerHTML = '<p class="text-center text-gray-500">Your cart is empty.</p>';
        placeOrderBtn.disabled = true;
        placeOrderBtn.classList.add('opacity-50', 'cursor-not-allowed');
        return;
    }

    // Render Items
    checkoutItemsContainer.innerHTML = cartState.items.map(item => `
        <div class="flex gap-4">
            <div class="w-16 h-20 bg-gray-100 rounded-sm overflow-hidden flex-shrink-0">
                <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <h4 class="font-serif text-base leading-tight mb-1">${item.name}</h4>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-xs text-brand-dark uppercase tracking-wider">Qty: ${item.quantity}</p>
                    <p class="text-sm font-medium">$${(item.price * item.quantity).toFixed(2)}</p>
                </div>
            </div>
        </div>
    `).join('');

    // Calculate Totals
    const total = cartState.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const totalStr = '$' + total.toFixed(2);

    subtotalEl.innerText = totalStr;
    totalEl.innerText = totalStr;

    // Handle Place Order
    placeOrderBtn.addEventListener('click', handlePlaceOrder);
}

function handlePlaceOrder() {
    const firstName = document.getElementById('first-name').value.trim();
    const lastName = document.getElementById('last-name').value.trim();
    const phoneInput = document.getElementById('payment-phone');
    const paymentMethodEl = document.querySelector('input[name="payment-method"]:checked');
    const paymentMethod = paymentMethodEl ? paymentMethodEl.value : 'cod';

    // Step 1: Validate Inputs
    if (!firstName || !lastName || !phoneInput || !phoneInput.value.trim()) {
        alert("Please fill in all required fields (First Name, Last Name, Phone).");
        return;
    }

    const customerName = `${firstName} ${lastName}`;
    const totalAmount = cartState.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // Step 2: Prepare Data
    const orderData = {
        customer_name: customerName,
        total_amount: totalAmount,
        items: cartState.items,
        payment_method: paymentMethod
    };

    // Step 3: Send to Backend
    fetch('place_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Order Placed Successfully! Order ID: " + data.order_id);
                clearCart();
                window.location.href = 'index.php';
            } else {
                alert("Failed to place order: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while placing the order.");
        });
}

function processRazorpayPayment() {
    const totalAmount = cartState.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    const options = {
        "key": "rzp_test_YOUR_KEY_HERE",
        "amount": Math.round(totalAmount * 100),
        "currency": "USD",
        "name": "Lumina",
        "description": "Purchase Description",
        "image": "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
        "handler": function (response) {
            alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
            clearCart();
            window.location.href = 'index.html';
        },
        "prefill": {
            "name": "Test User",
            "email": "test.user@example.com",
            "contact": "9999999999"
        },
        "theme": {
            "color": "#eeb4b4"
        }
    };

    const rzp1 = new Razorpay(options);
    rzp1.on('payment.failed', function (response) {
        alert("Payment Failed: " + response.error.description);
    });
    rzp1.open();
}

function clearCart() {
    cartState.items = [];
    saveCart();
    updateCartUI();
}
