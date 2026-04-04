@extends('layout.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="dashboard-title mb-0">Chi tiết ảnh phòng</h2>
    <a href="{{ route('anh-phong.index') }}" class="btn btn-secondary">Quay lại</a>
</div>

@php
    $previewPath = $anhPhong->loaiPhong
        ? asset($anhPhong->loaiPhong->roomImagePath($anhPhong->HinhAnh))
        : asset('img/Room/' . $anhPhong->HinhAnh);
@endphp

<table class="table table-bordered bg-white align-middle">
    <tr>
        <th style="width: 220px;">Mã ảnh</th>
        <td>{{ $anhPhong->MaAP }}</td>
    </tr>
    <tr>
        <th>Loại phòng</th>
        <td>{{ $anhPhong->loaiPhong?->TenLoai ?? 'Không xác định' }} ({{ $anhPhong->MaLoai }})</td>
    </tr>
    <tr>
        <th>Tên file</th>
        <td><code>{{ $anhPhong->HinhAnh }}</code></td>
    </tr>
    <tr>
        <th>Preview</th>
        <td><img src="{{ $previewPath }}" alt="{{ $anhPhong->HinhAnh }}" style="max-width: 340px; width: 100%; border-radius: 12px; border: 1px solid #ddd;"></td>
    </tr>
</table>
@endsection
