@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết HD Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-tour.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('hd-tour.edit', ['maHD' => $hdTour->MaHD, 'maLKH' => $hdTour->MaLKH]) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã HD:</th>
                <td>{{ $hdTour->MaHD }}</td>
            </tr>
            <tr>
                <th>Mã LKH:</th>
                <td>{{ $hdTour->MaLKH }}</td>
            </tr>
            <tr>
                <th>Số người lớn:</th>
                <td>{{ $hdTour->SoNguoiLon }}</td>
            </tr>
            <tr>
                <th>Số trẻ em:</th>
                <td>{{ $hdTour->SoTreEm }}</td>
            </tr>
            <tr>
                <th>Tổng tiền:</th>
                <td>{{ number_format($hdTour->TongTien, 2) }}</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td>{{ $hdTour->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            </tr>
            <tr>
                <th>Thanh toán:</th>
                <td>{{ $hdTour->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection