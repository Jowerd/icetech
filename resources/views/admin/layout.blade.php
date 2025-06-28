<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ადმინ პანელი • ICETECH')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- ძირითადი სტრუქტურის სტილები --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            overflow-x: hidden !important;
            width: 100%;
            max-width: 100%;
            background-color: #f8f9fa;
        }
        
        /* !!! შესწორებულია !!! .content სტილი დაბრუნდა პირვანდელ ვერსიაზე */
        .content {
            margin-left: 250px;
            padding: 20px; /* დაბრუნდა 20px-ზე */
            transition: margin-left 0.3s ease-in-out;
            width: calc(100vw - 250px);
            max-width: 100%;
        }

        /* ================================================================ */
        /* იზოლირებული სტილები მხოლოდ საიდბარისთვის                       */
        /* ================================================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #1C1D21;
            display: flex;
            flex-direction: column;
            padding: 20px 15px;
            overflow-y: hidden;
            border-right: 1px solid #2a2c31;
            font-family: 'Manrope', sans-serif;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }
        
        .sidebar .sidebar-header {
            padding-bottom: 20px;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }
        .sidebar .sidebar-header h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }
        .sidebar .sidebar-header i {
            color: #4dabf7;
            margin-right: 12px;
        }
        
        .sidebar .nav {
            height: 100%;
            overflow-y: auto;
        }
        .sidebar .nav::-webkit-scrollbar { width: 5px; }
        .sidebar .nav::-webkit-scrollbar-thumb { background: #495057; border-radius: 10px; }

        .sidebar .nav-link {
            color: #a8a9ae;
            font-weight: 500;
            padding: 13px 18px;
            margin-bottom: 5px;
            border-radius: 9px;
            position: relative;
            z-index: 1;
            overflow: hidden;
            transition: color 0.3s ease;
        }
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 9px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
            z-index: -1;
        }
        .sidebar .nav-link:hover::before {
            transform: scaleX(1);
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
        }
        
        .sidebar .nav-link.active {
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 50%;
            background: #4dabf7;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar .logout-item {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }
        .sidebar .logout-item .btn-danger {
            background-color: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff8a96;
            transition: all 0.3s ease;
        }
        .sidebar .logout-item .btn-danger:hover {
            background-color: #dc3545;
            color: #ffffff;
            border-color: #dc3545;
        }

        /* --- მობილურის სტილები --- */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            /* !!! შესწორებულია !!! .content სტილი მობილურისთვის დაბრუნდა პირვანდელ ვერსიაზე */
            .content {
                margin-left: 0;
                padding: 10px; /* დაბრუნდა 10px-ზე */
                width: 100%;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            .overlay.active {
                display: block;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>

<nav class="navbar navbar-dark bg-dark d-lg-none">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand">ICETECH</span>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0"><i class="bi bi-shield-check"></i> ადმინ პანელი</h4>
    </div>

    <ul class="nav flex-column h-100">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2 me-2"></i> მთავარი
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                <i class="bi bi-folder-fill me-2"></i> კატეგორიები
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="bi bi-box-seam me-2"></i> პროდუქტები
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                <i class="bi bi-chat-left-dots-fill me-2"></i> მიმოხილვები
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.blog.index') }}" class="nav-link">
                <i class="bi bi-journal-text me-2"></i> რჩევების ბლოგი
            </a>
        </li>

        <li class="nav-item logout-item">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> გასვლა
                </button>
            </form>
        </li>
    </ul>
</div>

<div class="overlay" id="overlay"></div>

<main class="content">
    @yield('content')
</main>

@yield('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let sidebar = document.getElementById("sidebar");
        let overlay = document.getElementById("overlay");
        let sidebarToggle = document.getElementById("sidebarToggle");
        const currentUrl = window.location.href;

        if (sidebarToggle) {
            sidebarToggle.addEventListener("click", function () {
                sidebar.classList.toggle("active");
                overlay.classList.toggle("active");
            });
        }

        if (overlay) {
            overlay.addEventListener("click", function () {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
            });
        }

        document.querySelectorAll('.sidebar .nav-link').forEach(function (link) {
            if (link.href === currentUrl) {
                link.classList.add('active');
            }
            link.addEventListener("click", function () {
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove("active");
                    overlay.classList.remove("active");
                }
            });
        });
    });
</script>

</body>
</html>