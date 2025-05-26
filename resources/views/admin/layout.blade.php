<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ადმინ პანელი • ICETECH')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden !important; /* ჰორიზონტალური სკროლის სრული გამორთვა */
            width: 100%;
            max-width: 100%;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            padding-top: 60px;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            box-shadow: 3px 0 6px rgba(0, 0, 0, 0.2);
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            white-space: nowrap;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
            width: calc(100vw - 250px);
            max-width: 100%;
        }

        /* მობილურისთვის */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 75%;
                max-width: 280px;
                padding-top: 20px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 10px;
                width: 100%;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- მობილური ნავბარი -->
<nav class="navbar navbar-dark bg-dark d-lg-none">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand">ICETECH</span>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-white text-center mb-4"><i class="bi bi-gear-fill"></i> ადმინ პანელი</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2 me-2"></i> მთავარი 1
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

        <!-- ✅ დამატებული ბლოგის ლინკი -->
        <li class="nav-item">
            <a href="{{ route('admin.blog.index') }}" class="nav-link">
                <i class="bi bi-journal-text me-2"></i> რჩევების ბლოგი
            </a>
        </li>

        <li class="nav-item px-3 mt-4">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> გასვლა
                </button>
            </form>
        </li>
    </ul>
</div>



<!-- მობილურისთვის Overlay (დაფარვა sidebar-ის უკან) -->
<div class="overlay" id="overlay"></div>

<!-- Content Section -->
<div class="content container-fluid">
    @yield('content')
</div>

@yield('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let sidebar = document.getElementById("sidebar");
        let overlay = document.getElementById("overlay");
        let sidebarToggle = document.getElementById("sidebarToggle");

        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
            overlay.classList.toggle("active");
        });

        overlay.addEventListener("click", function () {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        });

        document.querySelectorAll('.sidebar .nav-link').forEach(function (link) {
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
