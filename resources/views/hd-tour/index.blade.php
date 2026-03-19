@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý HD Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-tour.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Thêm mới</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('hd-tour.index') }}" class="mb-3">
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
                        <th>Mã LKH</th>
                        <th>Số người lớn</th>
                        <th>Số trẻ em</th>
                        <th>Tổng tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hdTour as $ht)
                    <tr>
                        <td>{{ $ht->MaHD }}</td>
                        <td>{{ $ht->MaLKH }}</td>
                        <td>{{ $ht->SoNguoiLon }}</td>
                        <td>{{ $ht->SoTreEm }}</td>
                        <td>{{ number_format($ht->TongTien, 2) }}</td>
                        <td>
                            <a href="{{ route('hd-tour.show', ['maHD' => $ht->MaHD, 'maLKH' => $ht->MaLKH]) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('hd-tour.edit', ['maHD' => $ht->MaHD, 'maLKH' => $ht->MaLKH]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('hd-tour.destroy', ['maHD' => $ht->MaHD, 'maLKH' => $ht->MaLKH]) }}" style="display:inline;">
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

        {{ $hdTour->appends(request()->query())->links() }}
    </div>
</div>
@endsection