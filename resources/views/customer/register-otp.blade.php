@extends('customer.layout.main')

@section('title', 'Xac thuc OTP - Khach hang')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Xác thực OTP đăng ký</h2>
                <p class="detail-muted">
                    Mã OTP sẽ được gửi đến email <strong>{{ $pendingRegistration['email'] ?? '' }}</strong> từ thông tin bạn đã nhập ở bước trước.
                </p>

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

                <div class="alert alert-light border mb-3">
                    <div><strong>Họ tên:</strong> {{ $pendingRegistration['ho_ten'] ?? '' }}</div>
                    <div><strong>Email:</strong> {{ $pendingRegistration['email'] ?? '' }}</div>
                    <div><strong>Số điện thoại:</strong> {{ $pendingRegistration['so_dien_thoai'] ?? '' }}</div>
                </div>

                <form method="POST" action="{{ route('customer.register.submit') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Mã OTP (6 số)</label>
                        <input
                            type="text"
                            name="otp"
                            maxlength="6"
                            class="form-control"
                            value="{{ old('otp') }}"
                            placeholder="Nhập mã OTP được gửi về email"
                            required
                        >
                        <small class="text-muted">OTP có hiệu lực trong 5 phút. Nếu chưa nhận được, bấm nút Nhận OTP để gửi lại.</small>
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('customer.register') }}" class="text-decoration-none">Quay lại nhập thông tin</a>
                        <div class="d-flex gap-2">
                            <button type="submit" formaction="{{ route('customer.register.send-otp') }}" name="resend" value="1" formnovalidate class="btn btn-outline-secondary">Nhận OTP</button>
                            <button type="submit" class="btn btn-book">Xác thực & đăng ký</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
