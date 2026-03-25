@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý hướng dẫn viên</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('huong-dan-vien.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Thêm hướng dẫn viên</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('huong-dan-vien.index') }}" class="row g-3 js-ajax-search" data-ajax-container="huong-dan-vien-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên, địa chỉ, số điện thoại..." value="{{ $search }}">
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

<div class="js-ajax-list" data-ajax-key="huong-dan-vien-list">
    @include('huong-dan-vien.partials.list')
</div>
@endsection