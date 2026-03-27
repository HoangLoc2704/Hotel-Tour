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

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('hd-dich-vu.index') }}" class="row g-3 js-ajax-search" data-ajax-container="hd-dich-vu-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
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

<div class="card js-ajax-list" data-ajax-key="hd-dich-vu-list">
    @include('hd-dich-vu.partials.list')
</div>
@endsection