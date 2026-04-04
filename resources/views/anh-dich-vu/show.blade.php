@extends('layout.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="dashboard-title mb-0">Chi tiết ảnh dịch vụ</h2>
    <a href="{{ route('anh-dich-vu.index') }}" class="btn btn-secondary">Quay lại</a>
</div>

@php
    $previewPath = $anhDichVu->dichVu
        ? asset($anhDichVu->dichVu->serviceImagePath($anhDichVu->HinhAnh))
        : asset('img/Service/' . $anhDichVu->HinhAnh);
@endphp

<table class="table table-bordered bg-white align-middle">
    <tr>
        <th style="width: 220px;">Mã ảnh</th>
        <td>{{ $anhDichVu->MaADV }}</td>
    </tr>
    <tr>
        <th>Dịch vụ</th>
        <td>{{ $anhDichVu->dichVu?->TenDV ?? 'Không xác định' }} ({{ $anhDichVu->MaDV }})</td>
    </tr>
    <tr>
        <th>Tên file</th>
        <td><code>{{ $anhDichVu->HinhAnh }}</code></td>
    </tr>
    <tr>
        <th>Preview</th>
        <td><img src="{{ $previewPath }}" alt="{{ $anhDichVu->HinhAnh }}" style="max-width: 340px; width: 100%; border-radius: 12px; border: 1px solid #ddd;"></td>
    </tr>
</table>
@endsection
