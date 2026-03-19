@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách Tour</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('tour.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<form method="GET" action="{{ route('tour.index') }}" class="row g-3 mb-4">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên" value="{{ request('search') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Tìm</button>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Mã</th>
            <th>Tên Tour</th>
            <th>Giá NL</th>
            <th>Giá TE</th>
            <th>Thời lượng</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tours as $tour)
        <tr>
            <td>{{ $tour->MaTour }}</td>
            <td>{{ $tour->TenTour }}</td>
            <td>{{ number_format($tour->GiaTourNguoiLon, 0, ',', '.') }}</td>
            <td>{{ number_format($tour->GiaTourTreEm, 0, ',', '.') }}</td>
            <td>{{ $tour->ThoiLuong }} ngày</td>
            <td>{{ $tour->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('tour.edit', $tour->MaTour) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('tour.destroy', $tour->MaTour) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tour này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $tours->withQueryString()->links() }}
@endsection