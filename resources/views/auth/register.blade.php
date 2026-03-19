<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký</title>

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

    .register_form {
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
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <form class="register_form" method="POST" action="{{ route('postRegister') }}">
        @csrf

        <div class="form_heading">Đăng Ký</div>

        @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <div class="input_group">
            <label>Tên đăng nhập</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form_control" required>
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="input_group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form_control" required>
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="input_group">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form_control" required>
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="input_group">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form_control" required>
            @error('password_confirmation')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Đăng ký</button>
    </form>

</body>

</html>