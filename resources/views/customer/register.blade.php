@extends('customer.layout.main')

@section('title', 'Dang ky - Khach hang')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Đăng ký tài khoản khách hàng</h2>
                <p class="detail-muted">Nhập thông tin, bấm Nhận mã OTP, sau đó nhập OTP và bấm Đăng ký để hoàn tất.</p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                <form method="POST" action="{{ route('customer.register.submit') }}" class="row g-3 mt-1">
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
                    <div class="col-12">
                        <label class="form-label">Mã OTP (6 số)</label>
                        <input type="text" name="otp" maxlength="6" class="form-control" value="{{ old('otp') }}" placeholder="Nhập OTP sau khi bấm Đăng ký lần đầu">
                        <small class="text-muted">OTP có hiệu lực trong 5 phút. Nếu hết hạn, bấm Nhận mã OTP để lấy mã mới.</small>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <a href="{{ route('customer.login') }}" class="text-decoration-none">Đã có tài khoản? Đăng nhập</a>
                        <div class="d-flex gap-2">
                            <button type="submit" formaction="{{ route('customer.register.send-otp') }}" class="btn btn-outline-secondary">Nhận mã OTP</button>
                            <button type="submit" class="btn btn-book">Đăng ký</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
