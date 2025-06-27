<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ადმინისტრატორის შესვლა</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            /* გაუმჯობესებული ფონი - შეგიძლიათ შეცვალოთ სურათი */
            background: url('https://source.unsplash.com/random/1920x1080/?abstract,geometric') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif; /* შრიფტის შეცვლა */
            margin: 0;
            overflow: hidden; /* რათა სურათი არ გაცდეს */
            position: relative;
        }

        body::before { /* მინისმორფული ფონის დაბურვა */
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1); /* ოდნავ თეთრი გამჭვირვალობა */
            backdrop-filter: blur(10px); /* დაბურვის ეფექტი */
            z-index: 1;
        }

        .login-container {
            position: relative; /* z-index-ისთვის */
            z-index: 2;
            background: rgba(255, 255, 255, 0.9); /* ოდნავ გამჭვირვალე ფონი */
            padding: 40px; /* მეტი სივრცე */
            border-radius: 20px; /* უფრო მომრგვალებული კუთხეები */
            /* ნეომორფული ჩრდილები */
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.1), -10px -10px 30px rgba(255, 255, 255, 0.8);
            width: 100%;
            max-width: 420px; /* ოდნავ ფართო */
            text-align: center;
            border: none;
            transition: all 0.3s ease;
            transform-style: preserve-3d; /* 3D ეფექტისთვის */
        }

        .login-container:hover {
            transform: translateY(-5px) scale(1.01); /* ოდნავ აწევა და გადიდება */
            box-shadow: 15px 15px 40px rgba(0, 0, 0, 0.15), -15px -15px 40px rgba(255, 255, 255, 0.9);
        }

        .login-container h2 {
            font-size: 28px; /* უფრო დიდი სათაური */
            font-weight: 700; /* უფრო თამამი */
            color: #2c3e50; /* მუქი ფერი */
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container h2 i {
            color: #3498db; /* ლურჯი */
            margin-right: 10px;
            font-size: 32px;
        }

        .form-label {
            text-align: left;
            display: block;
            margin-bottom: 6px;
            font-weight: 600; /* უფრო თამამი ლეიბლი */
            font-size: 15px;
            color: #34495e;
        }

        .input-group {
            margin-bottom: 20px; /* მეტი სივრცე ველებს შორის */
            border-radius: 12px; /* უფრო მომრგვალებული */
            overflow: hidden;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,0.05), inset -2px -2px 5px rgba(255,255,255,0.7); /* შიდა ჩრდილი */
            border: 1px solid #e0e0e0; /* რბილი საზღვარი */
        }

        .input-group-text {
            background: #ecf0f1; /* ღია ნაცრისფერი */
            border: none; /* საზღვრის მოხსნა */
            color: #3498db;
            padding: 15px; /* მეტი პედინგი */
            transition: all 0.3s ease;
        }

        .form-control {
            border: none; /* საზღვრის მოხსნა */
            font-size: 16px; /* უფრო დიდი შრიფტი */
            padding: 15px; /* მეტი პედინგი */
            background-color: #f9f9f9;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: #fff;
            outline: none;
            border: none;
        }

        .input-group:focus-within .input-group-text {
            background: #3498db; /* ფოკუსის დროს ფერის შეცვლა */
            color: #fff;
        }
        
        .input-group:focus-within {
            border-color: #3498db;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,0.1), inset -2px -2px 5px rgba(255,255,255,0.9), 0 0 0 3px rgba(52, 152, 219, 0.3); /* გარე ფოკუსის ეფექტი */
        }

        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9); /* უფრო ცოცხალი გრადიენტი */
            border: none;
            border-radius: 12px; /* უფრო მომრგვალებული */
            font-size: 18px; /* უფრო დიდი შრიფტი */
            font-weight: 600;
            padding: 14px;
            width: 100%;
            margin-top: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 15px rgba(52, 152, 219, 0.3); /* უფრო გამოკვეთილი ჩრდილი */
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #2980b9, #3498db);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.4);
            transform: translateY(-3px) scale(1.02); /* ოდნავ აწევა და გადიდება */
        }

        .btn-secondary {
            background: #f0f0f0; /* რბილი ნაცრისფერი */
            color: #555;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 500;
            padding: 14px;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }

        .btn-secondary:hover {
            background: #e0e0e0;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.12);
        }

        .alert-danger {
            background: #fef2f2; /* უფრო ღია წითელი ფონი */
            color: #cc0000; /* მუქი წითელი ტექსტი */
            border-left: 5px solid #e74c3c; /* უფრო სქელი საზღვარი */
            padding: 15px;
            border-radius: 10px;
            font-size: 15px;
            text-align: left;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(231, 76, 60, 0.1);
            animation: fadeInDown 0.5s ease-out; /* ანიმაცია გამოჩენისას */
        }

        .alert-danger i {
            color: #e74c3c;
            margin-right: 10px;
            font-size: 20px;
        }

        /* Password toggle icon style */
        #togglePassword {
            cursor: pointer;
            background: #ecf0f1;
            border: none;
            color: #3498db;
            padding: 15px;
            transition: all 0.3s ease;
        }

        #togglePassword:hover {
            background: #3498db;
            color: #fff;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2><i class="bi bi-shield-lock-fill"></i> ადმინისტრატორის შესვლა</h2>

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