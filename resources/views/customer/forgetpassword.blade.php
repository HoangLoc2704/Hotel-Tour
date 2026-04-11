@extends('customer.layout.main')

@section('title', 'Quên mật khẩu - Khách hàng')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Quên mật khẩu</h2>
                <p class="detail-muted">Nhập email tài khoản khách hàng để nhận mã OTP xác thực đặt lại mật khẩu.</p>

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

                <form method="POST" action="{{ route('customer.password.email') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Gmail tài khoản</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('customer.login') }}" class="text-decoration-none">Quay lại đăng nhập</a>
                        <button type="submit" class="btn btn-book">Xác nhận email</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
