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

<div class="card js-ajax-list" data-ajax-key="lich-khoi-hanh-list">
    @include('lich-khoi-hanh.partials.list')
</div>
@endsection