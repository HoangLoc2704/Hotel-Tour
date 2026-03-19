@extends('layout.main')

@section('content')
<h2 class="dashboard-title mb-2">Admin Dashboard</h2>
<p class="text-muted mb-4">Tổng quan các số liệu kinh doanh chính.</p>
<div class="row">
    <div class="col-md-3">
        <div class="card dashboard-card card-green">
            <div class="card-body">
                <h5 class="card-title">Tổng nhân viên</h5>
                <h3 class="card-text">{{ \App\Models\NhanVien::count() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card card-blue">
            <div class="card-body">
                <h5 class="card-title">Tổng khách hàng</h5>
                <h3 class="card-text">{{ \App\Models\KhachHang::count() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card card-cyan">
            <div class="card-body">
                <h5 class="card-title">Tổng phòng</h5>
                <h3 class="card-text">{{ \App\Models\Phong::count() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card card-green">
            <div class="card-body">
                <h5 class="card-title">Tổng hóa đơn</h5>
                <h3 class="card-text">{{ \App\Models\HoaDon::count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Thông tin tài khoản</h5>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $user['name'] }}</p>
                <p><strong>Email:</strong> {{ $user['email'] }}</p>
                <p><strong>Vai trò:</strong> {{ $user['role'] == 1 ? 'Quản lý' : 'Nhân viên' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Hệ thống quản lý</h5>
            </div>
            <div class="card-body">
                <p>Chào mừng bạn đến với hệ thống quản lý khách sạn và tour du lịch!</p>
                <p>Sử dụng menu bên trái để quản lý các chức năng của hệ thống.</p>
            </div>
        </div>
    </div>
</div>
@endsection

