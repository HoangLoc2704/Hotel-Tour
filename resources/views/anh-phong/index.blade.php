@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-7">
        <h2 class="dashboard-title">Quản lý ảnh phòng</h2>
        <p class="text-muted mb-0">Theo dõi và cập nhật ảnh trong bảng `tbl_AnhPhong`.</p>
    </div>
    <div class="col-md-5 text-end">
        <a href="{{ route('anh-phong.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm ảnh phòng</a>
    </div>
</div>

<form method="GET" action="{{ route('anh-phong.index') }}" class="row g-3 mb-4 js-ajax-search" data-ajax-container="anh-phong-list">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo loại phòng hoặc tên file..." value="{{ $search }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-secondary">Tìm</button>
    </div>
    <div class="col-auto">
        <a href="{{ route('anh-phong.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="js-ajax-list" data-ajax-key="anh-phong-list">
    @include('anh-phong.partials.list')
</div>
@endsection
