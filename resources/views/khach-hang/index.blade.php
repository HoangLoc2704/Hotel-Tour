@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách khách hàng</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('khach-hang.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('khach-hang.index') }}" class="row g-3 js-ajax-search" data-ajax-container="khach-hang-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên hoặc tài khoản" value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <button type="reset" class="btn btn-secondary w-100 js-search-reset">Reset</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="js-ajax-list" data-ajax-key="khach-hang-list">
    @include('khach-hang.partials.list')
</div>
@endsection