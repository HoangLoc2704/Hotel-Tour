@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách dịch vụ</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('dich-vu.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<form method="GET" action="{{ route('dich-vu.index') }}" class="row g-3 mb-4">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Tìm dịch vụ" value="{{ request('search') }}">
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
            <th>Tên dịch vụ</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dichVu as $dv)
        <tr>
            <td>{{ $dv->MaDV }}</td>
            <td>{{ $dv->TenDV }}</td>
            <td>{{ number_format($dv->GiaDV,0,',','.') }}</td>
            <td>{{ $dv->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('dich-vu.edit', $dv->MaDV) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                <a href="{{ route('dich-vu.show', $dv->MaDV) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                <form action="{{ route('dich-vu.destroy', $dv->MaDV) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa dịch vụ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $dichVu->withQueryString()->links() }}
@endsection