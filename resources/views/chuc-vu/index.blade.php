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

<div id="actionAlert"></div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('chuc-vu.index') }}" class="row g-3 js-ajax-search" data-ajax-container="chuc-vu-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên chức vụ..." value="{{ $search }}">
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

<div class="js-ajax-list" data-ajax-key="chuc-vu-list">
    @include('chuc-vu.partials.list')
</div>
@endsection