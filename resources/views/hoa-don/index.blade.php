@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách hóa đơn</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('hoa-don.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<form method="GET" action="{{ route('hoa-don.index') }}" class="row g-3 mb-4">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo mã HD hoặc tên KH" value="{{ request('search') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Tìm</button>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Mã HD</th>
            <th>Khách hàng</th>
            <th>Ngày tạo</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hoaDon as $hd)
        <tr>
            <td>{{ $hd->MaHD }}</td>
            <td>{{ $hd->khachHang->TenKH ?? '' }}</td>
            <td>{{ $hd->NgayTao }}</td>
            <td>{{ number_format($hd->ThanhTien, 0, ',', '.') }}</td>
            <td>{{ $hd->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('hoa-don.edit', $hd->MaHD) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                <a href="{{ route('hoa-don.show', $hd->MaHD) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                <form action="{{ route('hoa-don.destroy', $hd->MaHD) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa hóa đơn?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $hoaDon->withQueryString()->links() }}
@endsection