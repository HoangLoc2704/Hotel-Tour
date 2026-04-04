@extends('layout.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="dashboard-title mb-0">Chi tiết ảnh tour</h2>
    <a href="{{ route('anh-tour.index') }}" class="btn btn-secondary">Quay lại</a>
</div>

@php
    $previewPath = $anhTour->tour
        ? asset($anhTour->tour->tourImagePath($anhTour->HinhAnh))
        : asset('img/Tour/' . $anhTour->HinhAnh);
@endphp

<table class="table table-bordered bg-white align-middle">
    <tr>
        <th style="width: 220px;">Mã ảnh</th>
        <td>{{ $anhTour->MaAT }}</td>
    </tr>
    <tr>
        <th>Tour</th>
        <td>{{ $anhTour->tour?->TenTour ?? 'Không xác định' }} ({{ $anhTour->MaTour }})</td>
    </tr>
    <tr>
        <th>Tên file</th>
        <td><code>{{ $anhTour->HinhAnh }}</code></td>
    </tr>
    <tr>
        <th>Preview</th>
        <td><img src="{{ $previewPath }}" alt="{{ $anhTour->HinhAnh }}" style="max-width: 340px; width: 100%; border-radius: 12px; border: 1px solid #ddd;"></td>
    </tr>
</table>
@endsection
