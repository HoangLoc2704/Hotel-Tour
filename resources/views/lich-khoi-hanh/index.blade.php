@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý lịch khởi hành</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('lich-khoi-hanh.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Thêm mới</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('lich-khoi-hanh.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Tìm</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã lịch</th>
                        <th>Tour</th>
                        <th>Ngày khởi hành</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lichKhoiHanh as $lkh)
                    <tr>
                        <td>{{ $lkh->MaLich }}</td>
                        <td>{{ $lkh->tour->TenTour ?? 'N/A' }}</td>
                        <td>{{ $lkh->NgayKhoiHanh }}</td>
                        <td>{{ $lkh->ThoiGian }}</td>
                        <td>{{ $lkh->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
                        <td>
                            <a href="{{ route('lich-khoi-hanh.show', $lkh->MaLich) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('lich-khoi-hanh.edit', $lkh->MaLich) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('lich-khoi-hanh.destroy', $lkh->MaLich) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $lichKhoiHanh->appends(request()->query())->links() }}
    </div>
</div>
@endsection