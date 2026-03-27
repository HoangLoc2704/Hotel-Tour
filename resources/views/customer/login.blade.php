@extends('customer.layout.main')

@section('title', 'Đăng nhập - Khách hàng')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Đăng nhập khách hàng</h2>
                <p class="detail-muted">Đăng nhập để theo dõi và sử dụng các dịch vụ dành cho khách hàng.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.login.submit') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="mat_khau" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-book">Đăng nhập</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
