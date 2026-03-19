@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý Chức vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('chuc-vu.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm chức vụ
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('chuc-vu.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên chức vụ..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên chức vụ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($chucVu as $cv)
                    <tr>
                        <td>{{ $cv->MaCV }}</td>
                        <td>{{ $cv->TenCV }}</td>
                        <td>
                            <a href="{{ route('chuc-vu.edit', $cv->MaCV) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form method="POST" action="{{ route('chuc-vu.destroy', $cv->MaCV) }}" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-4 text-muted">Không có chức vụ nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $chucVu->appends(request()->query())->links() }}
</div>
@endsection