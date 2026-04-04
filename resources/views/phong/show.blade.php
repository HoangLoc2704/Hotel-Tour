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

<div class="alert alert-info">
    Thông tin <strong>giá, sức chứa, hình ảnh và mô tả</strong> đang được lấy từ <strong>loại phòng</strong> tương ứng.
</div>

<table class="table table-bordered align-middle">
    <tr>
        <th style="width: 220px;">Mã phòng</th>
        <td>{{ $phong->MaPhong }}</td>
    </tr>
    <tr>
        <th>Tên / mã phòng</th>
        <td>{{ $phong->TenPhong }}</td>
    </tr>
    <tr>
        <th>Loại phòng</th>
        <td>{{ $phong->loaiPhong->TenLoai ?? '-' }}</td>
    </tr>
    <tr>
        <th>Giá phòng / đêm</th>
        <td>{{ number_format($phong->GiaPhong ?? 0, 0, ',', '.') }} VNĐ</td>
    </tr>
    <tr>
        <th>Sức chứa</th>
        <td>{{ $phong->SoLuongNguoi ?? '-' }} người</td>
    </tr>
    <tr>
        <th>Mô tả</th>
        <td>{{ $phong->MoTa ?: 'Chưa có mô tả.' }}</td>
    </tr>
    <tr>
        <th>Hình ảnh</th>
        <td>
            @if(!empty($phong->HinhAnh))
                <div class="mb-2 text-muted">{{ $phong->HinhAnh }}</div>
                <img src="{{ asset($phong->roomImagePath()) }}" alt="{{ $phong->TenPhong }}" style="max-width: 320px; width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd;">
            @else
                <span class="text-muted">Chưa có hình ảnh.</span>
            @endif
        </td>
    </tr>
</table>
@endsection