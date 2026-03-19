@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="dashboard-title">Chi tiết chức vụ</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>ID:</strong> {{ $chucVu->MaCV }}</p>
        <p><strong>Tên chức vụ:</strong> {{ $chucVu->TenCV }}</p>
        <div class="mt-3">
            <a href="{{ route('chuc-vu.edit', $chucVu->MaCV) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
            <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        </div>
    </div>
</div>
@endsection