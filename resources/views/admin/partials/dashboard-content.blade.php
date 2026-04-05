<h2 class="dashboard-title mb-2">Admin Dashboard</h2>
<p class="text-muted mb-4">Tổng quan các số liệu kinh doanh chính.</p>
<div class="row g-3 dashboard-summary-row">
    <div class="col-md-6 col-xl-3 d-flex">
        <div class="card dashboard-card card-green w-100 h-100">
            <div class="card-body">
                <h5 class="card-title">Tổng nhân viên</h5>
                <h3 class="card-text">{{ $counts['nhan_vien'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 d-flex">
        <div class="card dashboard-card card-blue w-100 h-100">
            <div class="card-body">
                <h5 class="card-title">Tổng khách hàng</h5>
                <h3 class="card-text">{{ $counts['khach_hang'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 d-flex">
        <div class="card dashboard-card card-cyan w-100 h-100">
            <div class="card-body">
                <h5 class="card-title">Tổng phòng</h5>
                <h3 class="card-text">{{ $counts['phong'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 d-flex">
        <div class="card dashboard-card card-green w-100 h-100">
            <div class="card-body">
                <h5 class="card-title">Tổng hóa đơn</h5>
                <h3 class="card-text">{{ $counts['hoa_don'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0">Báo cáo doanh thu</h5>
        <small class="text-muted">Chỉ tính các hóa đơn đang hoạt động và đã thanh toán</small>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin') }}" class="row g-3 mb-4 js-ajax-search dashboard-report-form" data-ajax-container="admin-dashboard">
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="w-100">
                    <label class="form-label">Theo ngày</label>
                    <input type="date" name="report_date" class="form-control" value="{{ $filters['report_date'] ?? now()->toDateString() }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="w-100">
                    <label class="form-label">Theo tháng</label>
                    <input type="month" name="report_month" class="form-control" value="{{ $filters['report_month'] ?? now()->format('Y-m') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="w-100">
                    <label class="form-label">Theo năm</label>
                    <input type="number" name="report_year" class="form-control" min="2000" max="2100" value="{{ $filters['report_year'] ?? now()->year }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 d-flex align-items-end">
                <div class="d-flex gap-2 w-100 dashboard-report-actions">
                    <button type="submit" class="btn btn-primary flex-fill">Xem báo cáo</button>
                    <button type="reset" class="btn btn-outline-secondary js-search-reset flex-fill">Reset</button>
                </div>
            </div>
        </form>

        <div class="row g-3 dashboard-report-cards">
            <div class="col-md-4 d-flex">
                <div class="card border-success h-100 w-100">
                    <div class="card-body dashboard-report-card-body">
                        <h6 class="text-success">Doanh thu ngày</h6>
                        <div class="small text-muted mb-2">{{ $revenueReport['day']['label'] }}</div>
                        <h4 class="mb-1">{{ number_format($revenueReport['day']['total'], 0, ',', '.') }} VND</h4>
                        <div class="text-muted">{{ $revenueReport['day']['count'] }} hóa đơn</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card border-primary h-100 w-100">
                    <div class="card-body dashboard-report-card-body">
                        <h6 class="text-primary">Doanh thu tháng</h6>
                        <div class="small text-muted mb-2">{{ $revenueReport['month']['label'] }}</div>
                        <h4 class="mb-1">{{ number_format($revenueReport['month']['total'], 0, ',', '.') }} VND</h4>
                        <div class="text-muted">{{ $revenueReport['month']['count'] }} hóa đơn</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card border-warning h-100 w-100">
                    <div class="card-body dashboard-report-card-body">
                        <h6 class="text-warning">Doanh thu năm</h6>
                        <div class="small text-muted mb-2">{{ $revenueReport['year']['label'] }}</div>
                        <h4 class="mb-1">{{ number_format($revenueReport['year']['total'], 0, ',', '.') }} VND</h4>
                        <div class="text-muted">{{ $revenueReport['year']['count'] }} hóa đơn</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-3">
    <div class="col-md-6 d-flex">
        <div class="card h-100 w-100">
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

    <div class="col-md-6 d-flex">
        <div class="card h-100 w-100">
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
