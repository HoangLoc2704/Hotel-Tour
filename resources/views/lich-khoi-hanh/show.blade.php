@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết lịch khởi hành</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('lich-khoi-hanh.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('lich-khoi-hanh.edit', $lichKhoiHanh->MaLKH) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã lịch:</th>
                <td>{{ $lichKhoiHanh->MaLKH }}</td>
            </tr>
            <tr>
                <th>Tour:</th>
                <td>{{ $lichKhoiHanh->tour->TenTour ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ngày khởi hành:</th>
                <td>{{ $lichKhoiHanh->NgayKhoiHanh }}</td>
            </tr>
            <tr>
                <th>Ngày kết thúc:</th>
                <td>{{ $lichKhoiHanh->NgayKetThuc }}</td>
            </tr>
            <tr>
                <th>Số chỗ còn lại:</th>
                <td>{{ $lichKhoiHanh->SoChoConLai }}</td>
            </tr>
            <tr>
                <th>Hướng dẫn viên:</th>
                <td>{{ $lichKhoiHanh->huongDanVien->TenHDV ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Tài xế:</th>
                <td>{{ $lichKhoiHanh->TaiXe }}</td>
            </tr>
            <tr>
                <th>Phương tiện:</th>
                <td>{{ $lichKhoiHanh->PhuongTien }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection