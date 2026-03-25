<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý khách sạn</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ \App\Helpers\AssetHelper::assetVersion('css/admin.css') }}" rel="stylesheet">
    <script src="{{ \App\Helpers\AssetHelper::assetVersion('js/admin.js') }}" defer></script>

    <style>
    :root {
        --app-sidebar: #1f2d3d;
        --app-sidebar-hover: #293846;
        --app-brand: #0f5132;
        --app-brand-soft: #dff3e7;
        --app-border: #d7dee7;
        --app-text-muted: #6c7a89;
    }

    body {
        background-color: #f5f7fb;
    }

    .wrapper {
        display: flex;
    }

    /* SIDEBAR */
    .sidebar {
        width: 260px;
        background: var(--app-sidebar);
        height: 100vh;
        color: #c2c7d0;
        overflow-y: auto;
    }

    .sidebar-brand {
        background: var(--app-brand);
        padding: 15px;
        font-weight: bold;
        color: #fff;
        display: block;
        text-decoration: none;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
    }

    .sidebar-header {
        font-size: 12px;
        padding: 15px 20px 5px;
        color: #6c757d;
        text-transform: uppercase;
    }

    .sidebar-item a {
        display: block;
        padding: 10px 20px;
        color: #c2c7d0;
        text-decoration: none;
    }

    .sidebar-item a:hover {
        background: var(--app-sidebar-hover);
        color: #fff;
    }

    /* MAIN */
    .main {
        flex: 1;
    }

    /* NAVBAR */
    .navbar {
        background: #ffffff;
        padding: 12px 20px;
        border-bottom: 1px solid #dee2e6;
    }

    /* CARDS */
    .dashboard-card {
        border-radius: 12px;
        padding: 20px;
        color: #fff;
    }

    .card-green {
        background: #28a745;
    }

    .card-blue {
        background: #3b7ddd;
    }

    .card-cyan {
        background: #17a2b8;
    }

    .dashboard-title {
        font-size: 32px;
        font-weight: 600;
        color: #3b7ddd;
    }

    .footer {
        background: #fff;
        border-top: 1px solid #dee2e6;
        padding: 15px;
    }
    </style>
</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="{{ route('admin') }}" class="sidebar-brand">
                Quản lý khách sạn
            </a>

            <ul class="sidebar-nav">

                <li class="sidebar-header">HỆ THỐNG</li>

                <li class="sidebar-item">
                    <a href="{{ route('admin') }}">Bảng điều khiển</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('chuc-vu.index') }}">Bảng chức vụ</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('nhan-vien.index') }}">Quản lý nhân viên</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('huong-dan-vien.index') }}">Quản lý hướng dẫn viên</a>
                </li>

                <li class="sidebar-header">DANH MỤC</li>

                <li class="sidebar-item">
                    <a href="{{ route('loai-phong.index') }}">Quản lý loại phòng</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('phong.index') }}">Quản lý phòng</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('dich-vu.index') }}">Quản lý dịch vụ</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('tour.index') }}">Quản lý tour du lịch</a>
                </li>

                <li class="sidebar-header">KINH DOANH</li>

                <li class="sidebar-item">
                    <a href="{{ route('hoa-don.index') }}">Quản lý hóa đơn</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('khach-hang.index') }}">Quản lý khách hàng</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('lich-khoi-hanh.index') }}">Lịch khởi hành</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('hd-tour.index') }}">HD Tour</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('hd-dich-vu.index') }}">HD Dịch vụ</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('hd-phong.index') }}">HD Phòng</a>
                </li>

            </ul>
        </nav>

        <!-- Main -->
        <div class="main">

            <!-- Navbar -->
            <nav class="navbar d-flex justify-content-end">
                <span class="hamburger" id="toggleSidebar"> ☰ </span>

                <span class="me-3">Xin chào, {{ session('user_name') ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Đăng xuất</button>
                </form>
            </nav>

            <!-- Content -->
            <div class="container-fluid p-4">
                <div class="content g-4">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer text-center">
                Quản lý Khách sạn
            </footer>

        </div>

    </div>

</body>

</html>