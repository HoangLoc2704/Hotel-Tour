@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý HD Dịch vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-dich-vu.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Thêm mới</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('hd-dich-vu.index') }}" class="mb-3">
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
                        <th>Mã DV</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hdDichVu as $hdv)
                    <tr>
                        <td>{{ $hdv->MaHD }}</td>
                        <td>{{ $hdv->MaDV }}</td>
                        <td>{{ $hdv->SoLuong }}</td>
                        <td>{{ number_format($hdv->DonGia, 2) }}</td>
                        <td>
                            <a href="{{ route('hd-dich-vu.show', ['maHD' => $hdv->MaHD, 'maDV' => $hdv->MaDV]) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('hd-dich-vu.edit', ['maHD' => $hdv->MaHD, 'maDV' => $hdv->MaDV]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('hd-dich-vu.destroy', ['maHD' => $hdv->MaHD, 'maDV' => $hdv->MaDV]) }}" style="display:inline;">
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

        {{ $hdDichVu->appends(request()->query())->links() }}
    </div>
</div>
@endsection