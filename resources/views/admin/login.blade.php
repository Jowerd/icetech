<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ადმინისტრატორის შესვლა</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 380px;
            text-align: center;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .login-container:hover {
            transform: translateY(-3px);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.18);
        }

        .login-container h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }

        .login-container h2 i {
            color: #007bff;
            margin-right: 8px;
        }

        .form-label {
            text-align: left;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 5px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-right: none;
            color: #007bff;
            padding: 12px;
        }

        .form-control {
            border: 1px solid #e9ecef;
            border-left: none;
            font-size: 15px;
            padding: 12px;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #e9ecef;
            background-color: #fff;
        }

        .btn-primary {
            background: linear-gradient(to right, #0062cc, #007bff);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #005cbf, #0069d9);
            box-shadow: 0 6px 8px rgba(0, 123, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            color: #212529;
        }

        .alert-danger {
            background: #fff5f5;
            color: #e53e3e;
            border-left: 4px solid #e53e3e;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            text-align: left;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .alert-danger i {
            color: #e53e3e;
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2><i class="bi bi-shield-lock-fill"></i> შესვლა</h2>

        <!-- შეცდომის შეტყობინება -->
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ელფოსტა</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control" required placeholder="შეიყვანეთ ელფოსტა">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">პაროლი</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="შეიყვანეთ პაროლი">
                    <span class="input-group-text" id="togglePassword"><i class="bi bi-eye"></i></span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> შესვლა</button>
            <button type="button" class="btn btn-secondary" onclick="history.back();"><i class="bi bi-arrow-left"></i> უკან დაბრუნება</button>
        </form>
    </div>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function () {
            let passwordField = document.getElementById("password");
            let icon = this.querySelector("i");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    </script>

</body>
</html>