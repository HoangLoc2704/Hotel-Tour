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
                                <li><a class="dropdown-item" href="{{ route('customer.services.hotel') }}">Khách sạn</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.services.tour') }}">Tour du lịch</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.services.addon') }}">Dịch vụ đi kèm</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.booking') }}">Đặt dịch vụ</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Liên hệ</a>
                        </li>
                    </ul>

                    @if (session()->has('customer_user_id'))
                        <div class="d-flex align-items-center gap-2 customer-auth-box">
                            <a href="{{ route('customer.cart') }}#cart-section" class="btn btn-outline-light btn-sm customer-login-btn position-relative" title="Giỏ hàng" aria-label="Giỏ hàng">
                                Giỏ hàng
                                <span class="customer-cart-badge {{ count(session('customer_cart', [])) > 0 ? '' : 'd-none' }}" data-cart-count-badge>{{ count(session('customer_cart', [])) }}</span>
                            </a>
                            <a href="{{ route('customer.invoices') }}" class="btn btn-outline-light btn-sm customer-login-btn d-inline-flex align-items-center" title="Hóa đơn của tôi" aria-label="Hóa đơn của tôi">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 0a5 5 0 1 0 0 10A5 5 0 0 0 8 0zM4.5 5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    <path d="M14 16s-1-4-6-4-6 4-6 4h12z"/>
                                </svg>
                            </a>
                            <span class="customer-auth-name">{{ session('customer_user_name') }}</span>
                            <form method="POST" action="{{ route('customer.logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm customer-login-btn">Đăng xuất</button>
                            </form>
                        </div>
                    @else
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('customer.cart') }}#cart-section" class="btn btn-outline-light btn-sm customer-login-btn position-relative" title="Giỏ hàng" aria-label="Giỏ hàng">
                                Giỏ hàng
                                <span class="customer-cart-badge {{ count(session('customer_cart', [])) > 0 ? '' : 'd-none' }}" data-cart-count-badge>{{ count(session('customer_cart', [])) }}</span>
                            </a>
                            <a href="{{ route('customer.register') }}" class="btn btn-light btn-sm customer-login-btn">Đăng ký</a>
                            <a href="{{ route('customer.login') }}" class="btn btn-outline-light btn-sm customer-login-btn">Đăng nhập</a>
                        </div>
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
    <script>
        window.getCurrentCustomerCartCount = function () {
            const badge = document.querySelector('[data-cart-count-badge]');
            const rawValue = badge?.textContent?.trim() ?? '0';
            const parsed = Number(rawValue);
            return Number.isFinite(parsed) ? Math.max(0, parsed) : 0;
        };

        window.updateCustomerCartUI = function (count, options = {}) {
            const currentCount = window.getCurrentCustomerCartCount();
            const parsedCount = Number(count);
            const incrementBy = Number(options.incrementBy ?? 0);
            const fallbackCount = Number.isFinite(incrementBy)
                ? currentCount + Math.max(0, incrementBy)
                : currentCount;
            const safeCount = Number.isFinite(parsedCount)
                ? Math.max(0, parsedCount)
                : Math.max(0, fallbackCount);

            document.querySelectorAll('[data-cart-count-badge]').forEach((element) => {
                element.textContent = String(safeCount);
                element.classList.toggle('d-none', safeCount <= 0);
            });

            document.querySelectorAll('[data-cart-count-text]').forEach((element) => {
                element.textContent = String(safeCount);
            });
        };
    </script>
    @stack('scripts')
</body>

</html>
