@extends('customer.layout.main')

@section('title', 'Xác thực OTP - Quên mật khẩu')

@section('content')
    <main class="container py-5">
        <section class="login-shell">
            <div class="login-card">
                <h2 class="mb-3">Xác thực OTP</h2>
                <p class="detail-muted">
                    Mã OTP đã được gửi đến email <strong>{{ $resetPayload['email'] ?? '' }}</strong>. Vui lòng nhập mã để xác nhận email.
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
                    <div><strong>Khách hàng:</strong> {{ $resetPayload['customer_name'] ?? '' }}</div>
                    <div><strong>Email:</strong> {{ $resetPayload['email'] ?? '' }}</div>
                </div>

                <form method="POST" action="{{ route('customer.password.verify-otp') }}" class="row g-3 mt-1">
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
                        <small class="text-muted">OTP có hiệu lực trong 5 phút. Nếu chưa nhận được, bạn có thể yêu cầu gửi lại.</small>
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('customer.password.request') }}" class="text-decoration-none">Quay lại nhập email</a>
                        <div class="d-flex gap-2">
                            <button type="submit" formaction="{{ route('customer.password.email') }}" name="resend" value="1" formnovalidate class="btn btn-outline-secondary">Gửi lại OTP</button>
                            <button type="submit" class="btn btn-book">Xác thực</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
