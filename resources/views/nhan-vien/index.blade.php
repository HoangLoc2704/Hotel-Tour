@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý Nhân viên</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('nhan-vien.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm nhân viên
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Form Tìm kiếm -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('nhan-vien.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email hoặc SĐT..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('nhan-vien.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Bảng danh sách nhân viên -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên nhân viên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Chức vụ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nhanVien as $nv)
                    <tr>
                        <td>{{ $nv->MaNV }}</td>
                        <td>{{ $nv->TenNV }}</td>
                        <td>{{ $nv->Email }}</td>
                        <td>{{ $nv->SDT }}</td>
                        <td>
                            <span class="badge bg-info">{{ $nv->chucVu->TenCV ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if ($nv->TrangThai == 1)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Vô hiệu</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('nhan-vien.show', $nv->MaNV) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                            <a href="{{ route('nhan-vien.edit', $nv->MaNV) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form method="POST" action="{{ route('nhan-vien.destroy', $nv->MaNV) }}" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Không có nhân viên nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Phân trang -->
<div class="d-flex justify-content-center mt-4">
    {{ $nhanVien->appends(request()->query())->links() }}
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection
