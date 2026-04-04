@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết loại phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('loai-phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<table class="table table-bordered align-middle">
    <tr>
        <th style="width: 220px;">Mã loại</th>
        <td>{{ $loaiPhong->MaLoai }}</td>
    </tr>
    <tr>
        <th>Tên loại</th>
        <td>{{ $loaiPhong->TenLoai }}</td>
    </tr>
    <tr>
        <th>Giá phòng / đêm</th>
        <td>{{ number_format($loaiPhong->GiaPhong ?? 0, 0, ',', '.') }} VNĐ</td>
    </tr>
    <tr>
        <th>Sức chứa</th>
        <td>{{ $loaiPhong->SoLuongNguoi ?? '-' }} người</td>
    </tr>
    <tr>
        <th>Mô tả</th>
        <td>{{ $loaiPhong->MoTa ?: 'Chưa có mô tả.' }}</td>
    </tr>
    <tr>
        <th>Hình ảnh</th>
        <td>
            @if(!empty($loaiPhong->HinhAnh))
                <div class="mb-2 text-muted">{{ $loaiPhong->HinhAnh }}</div>
                <img src="{{ asset($loaiPhong->roomImagePath()) }}" alt="{{ $loaiPhong->TenLoai }}" style="max-width: 320px; width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd;">
            @else
                <span class="text-muted">Chưa có hình ảnh.</span>
            @endif
        </td>
    </tr>
</table>
@endsection