@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết lịch khởi hành</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('lich-khoi-hanh.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('lich-khoi-hanh.edit', $lichKhoiHanh->MaLich) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã lịch:</th>
                <td>{{ $lichKhoiHanh->MaLich }}</td>
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
                <th>Thời gian:</th>
                <td>{{ $lichKhoiHanh->ThoiGian }}</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td>{{ $lichKhoiHanh->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection