@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<table class="table">
    <tr>
        <th>ID</th>
        <td>{{ $phong->MaPhong }}</td>
    </tr>
    <tr>
        <th>Tên phòng</th>
        <td>{{ $phong->TenPhong }}</td>
    </tr>
    <tr>
        <th>Loại</th>
        <td>{{ $phong->loaiPhong->TenLoai ?? '' }}</td>
    </tr>
    <tr>
        <th>Giá</th>
        <td>{{ number_format($phong->GiaPhong,0,',','.') }}</td>
    </tr>
    <tr>
        <th>Số lượng người</th>
        <td>{{ $phong->SoLuongNguoi }}</td>
    </tr>
    <tr>
        <th>Mô tả</th>
        <td>{{ $phong->MoTa }}</td>
    </tr>
    <tr>
        <th>Hình ảnh</th>
        <td>{{ $phong->HinhAnh }}</td>
    </tr>
</table>
@endsection