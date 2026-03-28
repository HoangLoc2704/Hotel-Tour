@extends('customer.layout.main')

@section('title', 'Chi tiết hóa đơn')

@section('content')
    <main class="container py-5">
        <section>
            <div class="booking-wrap">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div class="section-title-wrap mb-0">
                        <h2>Chi tiết hóa đơn #{{ $hoaDon->MaHD }}</h2>
                        <p>Thông tin đầy đủ của hóa đơn thuộc tài khoản khách hàng đang đăng nhập.</p>
                    </div>
                    <a href="{{ route('customer.invoices') }}" class="btn btn-outline-secondary">Quay lại danh sách</a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <strong>Mã hóa đơn:</strong> #{{ $hoaDon->MaHD }}
                            </div>
                            <div class="col-md-4">
                                <strong>Ngày tạo:</strong> {{ $hoaDon->NgayTao }}
                            </div>
                            <div class="col-md-4">
                                <strong>Tổng tiền:</strong> {{ number_format((float) $hoaDon->ThanhTien, 0, ',', '.') }} VND
                            </div>
                            <div class="col-md-4">
                                <strong>Khách hàng:</strong> {{ $hoaDon->khachHang->TenKH ?? session('customer_user_name') }}
                            </div>
                            <div class="col-md-4">
                                <strong>SĐT:</strong> {{ $hoaDon->khachHang->SDT ?? session('customer_user_phone') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Email:</strong> {{ $hoaDon->khachHang->Email ?? session('customer_user_email') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Trạng thái:</strong>
                                <span class="badge text-bg-{{ (int) $hoaDon->TrangThai === 1 ? 'success' : 'secondary' }}">
                                    {{ (int) $hoaDon->TrangThai === 1 ? 'Hoạt động' : 'Vô hiệu' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Thanh toán:</strong>
                                <span class="badge text-bg-{{ (int) $hoaDon->ThanhToan === 1 ? 'success' : 'warning' }}">
                                    {{ (int) $hoaDon->ThanhToan === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($hoaDon->hdPhongs->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Chi tiết phòng</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Mã phòng</th>
                                            <th>Tên phòng</th>
                                            <th>Nhận phòng</th>
                                            <th>Trả phòng</th>
                                            <th>Trạng thái</th>
                                            <th>Thanh toán</th>
                                            <th class="text-end">Tổng tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hoaDon->hdPhongs as $item)
                                            <tr>
                                                <td>{{ $item->MaPhong }}</td>
                                                <td>{{ $item->phong->TenPhong ?? '-' }}</td>
                                                <td>{{ $item->NgayNhanPhong }}</td>
                                                <td>{{ $item->NgayTraPhong }}</td>
                                                <td>{{ (int) $item->TrangThai === 1 ? 'Hoạt động' : 'Vô hiệu' }}</td>
                                                <td>{{ (int) $item->ThanhToan === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                                                <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($hoaDon->hdDichVus->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Chi tiết dịch vụ</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Mã DV</th>
                                            <th>Tên dịch vụ</th>
                                            <th>Số lượng</th>
                                            <th>Trạng thái</th>
                                            <th>Thanh toán</th>
                                            <th class="text-end">Tổng tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hoaDon->hdDichVus as $item)
                                            <tr>
                                                <td>{{ $item->MaDV }}</td>
                                                <td>{{ $item->dichVu->TenDV ?? '-' }}</td>
                                                <td>{{ $item->SoLuong }}</td>
                                                <td>{{ (int) $item->TrangThai === 1 ? 'Hoạt động' : 'Vô hiệu' }}</td>
                                                <td>{{ (int) $item->ThanhToan === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                                                <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($hoaDon->hdTours->isNotEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Chi tiết tour</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Mã lịch</th>
                                            <th>Tên tour</th>
                                            <th>Khởi hành</th>
                                            <th>Kết thúc</th>
                                            <th>Người lớn</th>
                                            <th>Trẻ em</th>
                                            <th>Trạng thái</th>
                                            <th>Thanh toán</th>
                                            <th class="text-end">Tổng tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hoaDon->hdTours as $item)
                                            <tr>
                                                <td>{{ $item->MaLKH }}</td>
                                                <td>{{ $item->lichKhoiHanh->tour->TenTour ?? '-' }}</td>
                                                <td>{{ $item->lichKhoiHanh->NgayKhoiHanh ?? '-' }}</td>
                                                <td>{{ $item->lichKhoiHanh->NgayKetThuc ?? '-' }}</td>
                                                <td>{{ (int) $item->SoNguoiLon }}</td>
                                                <td>{{ (int) $item->SoTreEm }}</td>
                                                <td>{{ (int) $item->TrangThai === 1 ? 'Hoạt động' : 'Vô hiệu' }}</td>
                                                <td>{{ (int) $item->ThanhToan === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                                                <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection