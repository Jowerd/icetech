<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard • ICETECH')</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #0f172a; /* Deep Navy - უფრო სოლიდური ვიდრე შავი */
            --accent-color: #3b82f6; /* Modern Blue */
            --text-muted: #94a3b8;
            --bg-light: #f1f5f9;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Manrope', sans-serif;
            background-color: var(--bg-light);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* --- Sidebar Modern Style --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            z-index: 1050;
            transition: var(--transition);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            padding: 0 12px 30px 12px;
            display: flex;
            align-items: center;
        }

        .sidebar-header .logo-box {
            background: linear-gradient(135deg, var(--accent-color), #2563eb);
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
            font-size: 1.2rem;
        }

        .sidebar-header h4 {
            font-family: 'Manrope', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #f8fafc;
            margin: 0;
        }

        /* Nav Links */
        .sidebar .nav {
            flex-grow: 1;
        }

        .nav-label {
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0 10px 12px;
        }

        .sidebar .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 12px 16px;
            margin-bottom: 4px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--accent-color);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .sidebar .nav-link.active i {
            color: white;
        }

        /* Logout Section */
        .logout-item {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-logout {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid transparent;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }

        /* --- Main Content Area --- */
        .content {
            margin-left: 280px;
            padding: 40px;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Top Bar for Mobile */
        .mobile-header {
            display: none;
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #e2e8f0;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }

        /* --- Responsiveness --- */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
                padding: 20px;
            }
            .mobile-header {
                display: flex;
            }
            .overlay.active {
                display: block;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    
    @yield('styles')
</head>
<body>

<div class="overlay" id="overlay"></div>

<header class="mobile-header">
    <div class="d-flex align-items-center">
        <div class="bg-primary text-white p-2 rounded me-2" style="line-height: 0;">
            <i class="bi bi-cpu-fill"></i>
        </div>
        <span class="fw-bold">ICETECH</span>
    </div>
    <button class="btn btn-light border" id="sidebarToggle">
        <i class="bi bi-list fs-4"></i>
    </button>
</header>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4>ICETECH ADMIN</h4>
    </div>

    <div class="nav-label">Menu</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i> მთავარი
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                <i class="bi bi-tags-fill"></i> კატეგორიები
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="bi bi-bag-check-fill"></i> პროდუქტები
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.slides.index') }}" class="nav-link">
                <i class="bi bi-collection-play-fill"></i> ბანერის სლაიდები
            </a>
        </li>

        <div class="nav-label">Content & Community</div>
        <li class="nav-item">
            <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                <i class="bi bi-chat-square-text-fill"></i> მიმოხილვები
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.blog.index') }}" class="nav-link">
                <i class="bi bi-journal-bookmark-fill"></i> რჩევების ბლოგი
            </a>
        </li>
    </ul>

    <div class="logout-item">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left me-2"></i> სისტემიდან გასვლა
            </button>
        </form>
    </div>
</aside>

<main class="content">
    <div class="container-fluid p-0">
        @yield('content')
    </div>
</main>

@yield('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const sidebarToggle = document.getElementById("sidebarToggle");
        const currentUrl = window.location.href;

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar.classList.toggle("active");
            overlay.classList.toggle("active");
        }

        if (sidebarToggle) sidebarToggle.addEventListener("click", toggleSidebar);
        if (overlay) overlay.addEventListener("click", toggleSidebar);

        // Active Link Logic
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
            }
        });
    });
</script>

</body>
</html>