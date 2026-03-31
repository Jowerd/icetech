<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ავტორიზაცია • ICETECH ADMIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-blue: #0d6efd;
            --dark-navy: #1e293b;
            --soft-bg: #f8fafc;
        }

        body {
            background-color: var(--soft-bg);
            /* მსუბუქი გეომეტრიული პატერნი ფონისთვის */
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 4px; /* უფრო მკაცრი კუთხეები */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-top: 4px solid var(--primary-blue);
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--dark-navy);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .brand-logo span {
            color: var(--primary-blue);
        }

        .login-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #475569;
        }

        .input-group {
            border: 2px solid #e2e8f0;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .input-group:focus-within {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding-left: 1rem;
        }

        .form-control {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: var(--dark-navy);
        }

        .form-control:focus {
            box-shadow: none;
            background-color: transparent;
        }

        #togglePassword {
            cursor: pointer;
            border: none;
            background: transparent;
            color: #94a3b8;
            padding-right: 1rem;
        }

        #togglePassword:hover {
            color: var(--primary-blue);
        }

        .btn-primary {
            background-color: var(--dark-navy);
            border: none;
            border-radius: 4px;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            transition: all 0.2s;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            background-color: #0f172a;
            transform: translateY(-1px);
        }

        .btn-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .btn-link:hover {
            color: var(--primary-blue);
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #b91c1c;
            font-size: 0.85rem;
            border-radius: 4px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        /* პატარა დეტალი: ანიმაცია შემოსვლისას */
        .login-card {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-2">
            <div class="brand-logo">ICE<span>TECH</span></div>
            <div class="login-header">
                <h2>ადმინისტრატორის პანელი</h2>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ელფოსტა</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="email" name="email" class="form-control" required placeholder="admin@icetech.ge" autofocus>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label">პაროლი</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
                    <button type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                შესვლა <i class="bi bi-chevron-right ms-1 small"></i>
            </button>
            
            <div class="text-center">
                <a href="/" class="btn-link"><i class="bi bi-house me-1"></i> მთავარზე დაბრუნება</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function () {
            const passwordField = document.getElementById("password");
            const icon = this.querySelector("i");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        });
    </script>

</body>
</html>