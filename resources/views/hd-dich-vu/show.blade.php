@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết HD Dịch vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-dich-vu.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('hd-dich-vu.edit', ['maHD' => $hdDichVu->MaHD, 'maDV' => $hdDichVu->MaDV]) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã HD:</th>
                <td>{{ $hdDichVu->MaHD }}</td>
            </tr>
            <tr>
                <th>Mã DV:</th>
                <td>{{ $hdDichVu->MaDV }}</td>
            </tr>
            <tr>
                <th>Số lượng:</th>
                <td>{{ $hdDichVu->SoLuong }}</td>
            </tr>
            <tr>
                <th>Ngày sử dụng:</th>
                <td>{{ optional($hdDichVu->NgaySuDung)->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tổng tiền:</th>
                <td>{{ number_format($hdDichVu->TongTien, 2) }}</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td>{{ $hdDichVu->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            </tr>
            <tr>
                <th>Thanh toán:</th>
                <td>{{ $hdDichVu->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection