@extends('customer.layout.main')

@section('title', 'Đặt lại mật khẩu - Khách hàng')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Đặt lại mật khẩu</h2>
                <p class="detail-muted">Email <strong>{{ $resetPayload['email'] ?? '' }}</strong> đã được xác thực. Hãy nhập mật khẩu mới để hoàn tất.</p>

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

                <form method="POST" action="{{ route('customer.password.update') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="mat_khau" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="mat_khau_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('customer.login') }}" class="text-decoration-none">Quay lại đăng nhập</a>
                        <button type="submit" class="btn btn-book">Đổi mật khẩu</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
