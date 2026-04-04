@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách loại phòng</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('loai-phong.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('loai-phong.index') }}" class="row g-3 js-ajax-search" data-ajax-container="loai-phong-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên loại phòng..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Tìm</button>
            </div>
            <div class="col-md-2">
                <button type="reset" class="btn btn-outline-secondary w-100 js-search-reset">Reset</button>
            </div>
        </form>
    </div>
</div>

<div class="js-ajax-list" data-ajax-key="loai-phong-list">
    @include('loai-phong.partials.list')
</div>
@endsection