<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radiance & Rituals Shop - Admin Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --primary-pink: #d4a5a5;
            --soft-beige: #fdfbf7;
            --sidebar-bg: #ffffff;
            --text-color: #4a4a4a;
        }

        body {
            background-color: var(--soft-beige);
            color: var(--text-color);
            font-family: var(--bs-body-font-family);
        }

        /* Sidebar Styling */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #efefef;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-pink);
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-link {
            color: #6c757d;
            padding: 12px 25px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-pink);
            background-color: #fafafa;
            border-left-color: var(--primary-pink);
        }

        .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        /* Header Styling */
        .admin-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-bottom: 30px;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-name {
            font-weight: 500;
            color: #333;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-pink);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Card Styling to match theme */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            Radiance & Rituals
        </div>
        <div class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Manage Products</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-shopping-bag"></i>
                <span>Manage Orders</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <div class="mt-auto">
                <a href="#" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <header class="admin-header">
            <div class="admin-profile">
                <span class="admin-name">{{ Auth::user()->name ?? 'Admin User' }}</span>
                <div class="admin-avatar">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>