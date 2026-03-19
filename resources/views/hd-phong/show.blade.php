@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết HD Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('hd-phong.edit', ['maHD' => $hdPhong->MaHD, 'maPhong' => $hdPhong->MaPhong]) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã HD:</th>
                <td>{{ $hdPhong->MaHD }}</td>
            </tr>
            <tr>
                <th>Mã Phòng:</th>
                <td>{{ $hdPhong->MaPhong }}</td>
            </tr>
            <tr>
                <th>Số lượng:</th>
                <td>{{ $hdPhong->SoLuong }}</td>
            </tr>
            <tr>
                <th>Đơn giá:</th>
                <td>{{ number_format($hdPhong->DonGia, 2) }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection