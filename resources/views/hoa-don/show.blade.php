@extends('layout.main')

@php
    $formatDate = fn ($value) => filled($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
@endphp

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết hóa đơn</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hoa-don.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('hoa-don.edit', $hoaDon->MaHD) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
        <a href="{{ route('hoa-don.export-pdf', $hoaDon->MaHD) }}" target="_blank" class="btn btn-dark"><i class="bi bi-file-earmark-pdf"></i> Xuất PDF</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Mã HD:</th>
                <td>{{ $hoaDon->MaHD }}</td>
            </tr>
            <tr>
                <th>Khách hàng:</th>
                <td>{{ $hoaDon->khachHang->TenKH ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ngày tạo:</th>
                <td>{{ $formatDate($hoaDon->NgayTao) }}</td>
            </tr>
            <tr>
                <th>Thành tiền:</th>
                <td>{{ number_format($hoaDon->ThanhTien, 2) }}</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td>{{ $hoaDon->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            </tr>
            <tr>
                <th>Thanh toán:</th>
                <td>{{ $hoaDon->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection