@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="dashboard-title">Danh sách hóa đơn</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('hoa-don.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm mới</a>
    </div>
</div>

<form method="GET" action="{{ route('hoa-don.index') }}" class="row g-3 mb-4 js-ajax-search" data-ajax-container="hoa-don-list">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo mã HD hoặc tên KH" value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Tìm</button>
        </div>
        <div class="col-auto">
            <button type="reset" class="btn btn-outline-secondary js-search-reset">Reset</button>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="js-ajax-list" data-ajax-key="hoa-don-list">
    @include('hoa-don.partials.list')
</div>
@endsection