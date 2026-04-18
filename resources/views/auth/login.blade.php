<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - رينتالز ERP</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #2b3a4a 0%, #1a252f 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }

        .login-title {
            color: #2b3a4a;
            font-weight: 800;
            text-align: center;
            margin-bottom: 30px;
        }

        .login-title i {
            color: #e6b325;
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .form-control {
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #e6b325;
            box-shadow: 0 0 0 0.25rem rgba(230, 179, 37, 0.25);
        }

        .btn-login {
            background: #e6b325;
            color: #2b3a4a;
            font-weight: bold;
            padding: 12px;
            border-radius: 10px;
            border: none;
            width: 100%;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #d4a315;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-title">
            <i class="fa-solid fa-user-tie"></i>
            رينتالز ERP<br>
            <span class="fs-5 text-muted">تسجيل الدخول</span>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @error('login')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-3">
                <label for="login" class="form-label text-muted">اسم المستخدم، البريد، أو الهاتف</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user"></i></span>
                    <input type="text" id="login" name="login" class="form-control frst border-start-0 ps-0" placeholder="أدخل اسم المستخدم أو الهاتف أو الإيميل" required autofocus value="{{ old('login') }}">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-muted">كلمة المرور</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control frst border-start-0 ps-0" placeholder="أدخل كلمة المرور" required>
                </div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-muted" for="remember">تذكرني</label>
            </div>

            <button type="submit" class="btn btn-login">تسجيل الدخول <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i></button>
        </form>
    </div>

</body>
</html>
