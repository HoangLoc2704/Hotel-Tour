@extends('customer.layout.main')

@section('title', 'Hóa đơn của tôi')

@section('content')
    <main class="container py-5">
        <section>
            <div class="booking-wrap">
                <div class="section-title-wrap mb-3">
                    <h2>Hóa đơn của tôi</h2>
                    <p>Danh sách hóa đơn tương ứng với tài khoản khách hàng đang đăng nhập.</p>
                </div>

                <form method="GET" action="{{ route('customer.invoices') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Trạng thái thanh toán</label>
                        <select name="thanh_toan" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" @selected(($filters['thanh_toan'] ?? '') === '1')>Đã thanh toán</option>
                            <option value="0" @selected(($filters['thanh_toan'] ?? '') === '0')>Chưa thanh toán</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-book flex-grow-1">Lọc</button>
                        <a href="{{ route('customer.invoices') }}" class="btn btn-outline-secondary">Xóa</a>
                    </div>
                </form>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($hoaDons->isEmpty())
                    <div class="alert alert-info mb-0">Bạn chưa có hóa đơn nào.</div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach ($hoaDons as $hoaDon)
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white d-flex flex-wrap justify-content-between gap-2">
                                    <div>
                                        <strong>Mã hóa đơn:</strong> #{{ $hoaDon->MaHD }}
                                        <span class="ms-3"><strong>Ngày tạo:</strong> {{ $hoaDon->NgayTao }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge text-bg-{{ (int) $hoaDon->TrangThai === 1 ? 'success' : 'secondary' }}">
                                            {{ (int) $hoaDon->TrangThai === 1 ? 'Hoạt động' : 'Vô hiệu' }}
                                        </span>
                                        <span class="badge text-bg-{{ (int) $hoaDon->ThanhToan === 1 ? 'success' : 'warning' }}">
                                            {{ (int) $hoaDon->ThanhToan === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Tổng tiền:</strong>
                                        {{ number_format((float) $hoaDon->ThanhTien, 0, ',', '.') }} VND
                                    </div>

                                    <div class="mb-3">
                                        <a href="{{ route('customer.invoices.show', $hoaDon->MaHD) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                                    </div>

                                    @if ($hoaDon->hdPhongs->isNotEmpty())
                                        <div class="mb-3">
                                            <h6 class="mb-2">Chi tiết phòng</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã phòng</th>
                                                            <th>Tên phòng</th>
                                                            <th>Nhận phòng</th>
                                                            <th>Trả phòng</th>
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
                                                                <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($hoaDon->hdDichVus->isNotEmpty())
                                        <div class="mb-3">
                                            <h6 class="mb-2">Chi tiết dịch vụ</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã DV</th>
                                                            <th>Tên dịch vụ</th>
                                                            <th>Số lượng</th>
                                                            <th class="text-end">Tổng tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($hoaDon->hdDichVus as $item)
                                                            <tr>
                                                                <td>{{ $item->MaDV }}</td>
                                                                <td>{{ $item->dichVu->TenDV ?? '-' }}</td>
                                                                <td>{{ $item->SoLuong }}</td>
                                                                <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($hoaDon->hdTours->isNotEmpty())
                                        <div>
                                            <h6 class="mb-2">Chi tiết tour</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã lịch</th>
                                                            <th>Tên tour</th>
                                                            <th>Khởi hành</th>
                                                            <th>Kết thúc</th>
                                                            <th>NL/TE</th>
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
                                                                <td>{{ (int) $item->SoNguoiLon }}/{{ (int) $item->SoTreEm }}</td>
                                                                <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $hoaDons->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection