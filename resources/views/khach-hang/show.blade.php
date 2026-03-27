@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết khách hàng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('khach-hang.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<table class="table">
    <tr>
        <th>ID</th>
        <td>{{ $khachHang->MaKH }}</td>
    </tr>
    <tr>
        <th>Họ tên</th>
        <td>{{ $khachHang->TenKH }}</td>
    </tr>
    <tr>
        <th>Giới tính</th>
        <td>{{ $khachHang->GioiTinh ? 'Nam' : 'Nữ' }}</td>
    </tr>
    <tr>
        <th>SDT</th>
        <td>{{ $khachHang->SDT }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $khachHang->Email }}</td>
    </tr>
    <tr>
        <th>Trạng thái</th>
        <td>{{ $khachHang->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
    </tr>
</table>
@endsection