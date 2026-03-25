@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết hướng dẫn viên</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('huong-dan-vien.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('huong-dan-vien.edit', $huongDanVien->MaHDV) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã:</th>
                <td>{{ $huongDanVien->MaHDV }}</td>
            </tr>
            <tr>
                <th>Tên hướng dẫn viên:</th>
                <td>{{ $huongDanVien->TenHDV }}</td>
            </tr>
            <tr>
                <th>Ngày sinh:</th>
                <td>{{ $huongDanVien->NgaySinh }}</td>
            </tr>
            <tr>
                <th>Địa chỉ:</th>
                <td>{{ $huongDanVien->DiaChi }}</td>
            </tr>
            <tr>
                <th>SĐT:</th>
                <td>{{ $huongDanVien->SDT }}</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td>{{ $huongDanVien->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection