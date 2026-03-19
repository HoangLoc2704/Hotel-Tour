@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách khách hàng</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('khach-hang.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('khach-hang.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên hoặc tài khoản" value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('khach-hang.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tài khoản</th>
            <th>Họ tên</th>
            <th>SDT</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($khachHang as $kh)
        <tr>
            <td>{{ $kh->MaKH }}</td>
            <td>{{ $kh->TenTK }}</td>
            <td>{{ $kh->TenKH }}</td>
            <td>{{ $kh->SDT }}</td>
            <td>{{ $kh->Email }}</td>
            <td>{{ $kh->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('khach-hang.show', $kh->MaKH) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>Xem</a>
                <a href="{{ route('khach-hang.edit', $kh->MaKH) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i>Sửa</a>
                <form action="{{ route('khach-hang.destroy', $kh->MaKH) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa khách hàng?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i>Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $khachHang->withQueryString()->links() }}
@endsection