<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        margin: 0;
        height: 100vh;
        background: linear-gradient(135deg, #000000, #323232);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login_form {
        background: #ffffff;
        padding: 40px;
        width: 380px;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .form_heading {
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #3b7ddd;
    }

    .input_group {
        margin-bottom: 18px;
    }

    .input_group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        font-size: 14px;
    }

    .form_control {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: 0.3s;
        font-size: 14px;
    }

    .form_control:focus {
        border-color: #3b7ddd;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 125, 221, 0.2);
    }

    .text-danger {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 8px;
        background: #3b7ddd;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        font-size: 15px;
    }

    button:hover {
        background: #2f65c7;
    }

    @media (max-width: 480px) {
        .register_form {
            width: 90%;
            padding: 25px;
        }
    }

    </style>
</head>

<body>
    <form class="login_form" method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="form_heading">Đăng Nhập</div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="input_group">
            <label for="email">Email</label>
            <input class="form_control" type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Nhập email">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="input_group">
            <label for="password">Mật khẩu</label>
            <input class="form_control"type="password" name="mat_khau" id="password" required placeholder="Nhập mật khẩu">
            @error('mat_khau')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="forgot_password">
            <a href="#">Quên mật khẩu?</a>
        </div>

        <button class="submit" type="submit">Đăng nhập</button>

        <div style="margin-top: 14px; text-align: center;">
            <a href="{{ route('customer.index') }}" style="font-size: 14px; color: #3b7ddd; text-decoration: none;">Xem trang khách hàng</a>
        </div>
    </form>

    <script src="{{ asset('assets/js/login.js') }}"></script>
</body>

</html>