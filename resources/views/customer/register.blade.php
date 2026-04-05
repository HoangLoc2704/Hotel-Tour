@extends('customer.layout.main')

@section('title', 'Dang ky - Khach hang')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Đăng ký tài khoản khách hàng</h2>
                <p class="detail-muted">Nhập thông tin cần thiết. Hệ thống sẽ kiểm tra email, số điện thoại và chuyển bạn sang bước xác thực OTP.</p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.register.send-otp') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="mat_khau" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="mat_khau_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('customer.login') }}" class="text-decoration-none">Đã có tài khoản? Đăng nhập</a>
                        <button type="submit" class="btn btn-book">Tiếp tục xác thực OTP</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
