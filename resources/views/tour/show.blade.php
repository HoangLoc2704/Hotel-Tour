@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('tour.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<table class="table">
    <tr>
        <th>Mã</th>
        <td>{{ $tour->MaTour }}</td>
    </tr>
    <tr>
        <th>Tên tour</th>
        <td>{{ $tour->TenTour }}</td>
    </tr>
    <tr>
        <th>Giá người lớn</th>
        <td>{{ number_format($tour->GiaTourNguoiLon, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Giá trẻ em</th>
        <td>{{ number_format($tour->GiaTourTreEm, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Thời lượng</th>
        <td>{{ $tour->ThoiLuong }} ngày</td>
    </tr>
    <tr>
        <th>Địa điểm khởi hành</th>
        <td>{{ $tour->DiaDiemKhoiHanh }}</td>
    </tr>
    <tr>
        <th>Số lượng tối đa</th>
        <td>{{ $tour->SoLuongKhachToiDa }}</td>
    </tr>
    <tr>
        <th>Hình ảnh</th>
        <td>{{ $tour->HinhAnh }}</td>
    </tr>
    <tr>
        <th>Mô tả</th>
        <td>{{ $tour->MoTa }}</td>
    </tr>
    <tr>
        <th>Lịch trình</th>
        <td>{{ $tour->LichTrinh }}</td>
    </tr>
    <tr>
        <th>Trạng thái</th>
        <td>{{ $tour->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
    </tr>
</table>
@endsection