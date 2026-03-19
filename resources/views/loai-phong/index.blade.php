@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách loại phòng</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('loai-phong.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<form method="GET" action="{{ route('loai-phong.index') }}" class="row g-3 mb-4">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm" value="{{ request('search') }}">
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
            <th>Mã</th>
            <th>Tên loại</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loaiPhong as $loai)
        <tr>
            <td>{{ $loai->MaLoai }}</td>
            <td>{{ $loai->TenLoai }}</td>
            <td>
                <a href="{{ route('loai-phong.show', $loai->MaLoai) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>Xem</a>
                <a href="{{ route('loai-phong.edit', $loai->MaLoai) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i>Sửa</a>
                <form action="{{ route('loai-phong.destroy', $loai->MaLoai) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa loại phòng?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i>Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $loaiPhong->withQueryString()->links() }}
@endsection