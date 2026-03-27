<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Khách sạn - Dịch vụ cho khách hàng')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
</head>

<body id="top">
    <header class="customer-header sticky-top">
        <nav class="navbar navbar-expand-lg customer-navbar py-3">
            <div class="container">
                <a class="brand text-decoration-none" href="{{ route('customer.index') }}">Núi Cấm Hotel</a>

                <button class="navbar-toggler text-white border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavbar" aria-controls="customerNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="customerNavbar">
                    <ul class="navbar-nav mx-auto customer-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.index') }}">Trang chủ</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dịch vụ
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('customer.index') }}#hotel-services">Khách sạn</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.index') }}#tour-services">Tour du lịch</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.index') }}#addon-services">Dịch vụ đi kèm</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.booking') }}">Đặt phòng</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Liên hệ</a>
                        </li>
                    </ul>

                    @if (session()->has('customer_user_id'))
                        <div class="d-flex align-items-center gap-2 customer-auth-box">
                            <span class="customer-auth-name">{{ session('customer_user_name') }}</span>
                            <form method="POST" action="{{ route('customer.logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm customer-login-btn">Đăng xuất</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn btn-outline-light btn-sm customer-login-btn">Đăng nhập</a>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="customer-footer" id="contact">
        <div class="container py-4 d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>Núi Cấm Hotel · Khách sạn và dịch vụ du lịch cao cấp</span>
            <span>Hotline: 1900 0000 · Email: contact@aurora-hotel.vn</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
