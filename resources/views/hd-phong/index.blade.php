@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý HD Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-phong.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Thêm mới</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('hd-phong.index') }}" class="mb-3">
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
                        <th>Mã HD</th>
                        <th>Mã Phòng</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hdPhong as $hp)
                    <tr>
                        <td>{{ $hp->MaHD }}</td>
                        <td>{{ $hp->MaPhong }}</td>
                        <td>{{ $hp->SoLuong }}</td>
                        <td>{{ number_format($hp->DonGia, 2) }}</td>
                        <td>
                            <a href="{{ route('hd-phong.show', ['maHD' => $hp->MaHD, 'maPhong' => $hp->MaPhong]) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('hd-phong.edit', ['maHD' => $hp->MaHD, 'maPhong' => $hp->MaPhong]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('hd-phong.destroy', ['maHD' => $hp->MaHD, 'maPhong' => $hp->MaPhong]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $hdPhong->appends(request()->query())->links() }}
    </div>
</div>
@endsection