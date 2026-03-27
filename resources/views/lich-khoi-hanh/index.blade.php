@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý lịch khởi hành</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('lich-khoi-hanh.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Thêm mới</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('lich-khoi-hanh.index') }}" class="row g-3 js-ajax-search" data-ajax-container="lich-khoi-hanh-list">
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

<div class="card js-ajax-list" data-ajax-key="lich-khoi-hanh-list">
    @include('lich-khoi-hanh.partials.list')
</div>
@endsection