@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('phong.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Thêm phòng</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('phong.index') }}" class="row g-3 js-ajax-search" data-ajax-container="phong-list">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên hoặc địa chỉ..." value="{{ $search }}">
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

<div class="card js-ajax-list" data-ajax-key="phong-list">
    @include('phong.partials.list')
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection