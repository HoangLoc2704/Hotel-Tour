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
        <form method="GET" action="{{ route('nhan-vien.index') }}" class="row g-3 js-ajax-search" data-ajax-container="nhan-vien-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email hoặc SĐT..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <button type="reset" class="btn btn-secondary w-100 js-search-reset">Reset</button>
            </div>
        </form>
    </div>
</div>

<!-- Bảng danh sách nhân viên -->
<div class="card js-ajax-list" data-ajax-key="nhan-vien-list">
    @include('nhan-vien.partials.list')
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
