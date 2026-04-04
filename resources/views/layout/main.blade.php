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
</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="{{ route('admin') }}" class="sidebar-brand">
                Quản lý khách sạn
            </a>

            <ul class="sidebar-nav">
                @php
                $rawRoleName = session('user_role_name', '');
                $normalizedRole = \Illuminate\Support\Str::of($rawRoleName)
                ->lower()
                ->ascii()
                ->replaceMatches('/[^a-z0-9]+/', '-')
                ->trim('-')
                ->toString();

                $roleKey = match ($normalizedRole) {
                'quan-ly', 'manager' => 'quan-ly',
                'nhan-vien-le-tan', 'le-tan', 'receptionist' => 'le-tan',
                'nhan-vien-tour', 'tour', 'tour-staff' => 'nhan-vien-tour',
                default => $normalizedRole,
                };

                $isManager = $roleKey === 'quan-ly';
                $canManageExceptPersonnel = in_array($roleKey, ['quan-ly', 'le-tan'], true);
                $canAccessTourSchedule = in_array($roleKey, ['quan-ly', 'le-tan', 'nhan-vien-tour'], true);
                @endphp

                <li class="sidebar-header">HỆ THỐNG</li>

                <li class="sidebar-item">
                    <a href="{{ route('admin') }}">Bảng điều khiển</a>
                </li>

                @if ($isManager)
                <li class="sidebar-item">
                    <a href="{{ route('chuc-vu.index') }}">Bảng chức vụ</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('nhan-vien.index') }}">Quản lý nhân viên</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('huong-dan-vien.index') }}">Quản lý hướng dẫn viên</a>
                </li>
                @endif


                @if ($canManageExceptPersonnel)
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
                @endif

                @if ($canAccessTourSchedule)
                <li class="sidebar-item">
                    <a href="{{ route('tour.index') }}">Quản lý tour du lịch</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('lich-khoi-hanh.index') }}">Lịch khởi hành</a>
                </li>
                @endif

                <li class="sidebar-header">THƯ VIỆN ẢNH</li>

                <li class="sidebar-item">
                    <a href="{{ route('anh-phong.index') }}">Quản lý ảnh phòng</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('anh-tour.index') }}">Quản lý ảnh tour</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('anh-dich-vu.index') }}">Quản lý ảnh dịch vụ</a>
                </li>

                @if ($canManageExceptPersonnel)
                <li class="sidebar-header">KINH DOANH</li>

                <li class="sidebar-item">
                    <a href="{{ route('hoa-don.index') }}">Quản lý hóa đơn</a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('khach-hang.index') }}">Quản lý khách hàng</a>
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
                @endif

            </ul>
        </nav>

        <!-- Main -->
        <div class="main">

            <!-- Navbar -->
            <nav class="navbar d-flex justify-content-end">
                <span class="me-3">Xin chào, {{ session('user_name') ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Đăng xuất</button>
                </form>
            </nav>

            <div class="main-body">
                <!-- Content -->
                <div class="container-fluid p-4 main-content-scroll">
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

    </div>

</body>

</html>