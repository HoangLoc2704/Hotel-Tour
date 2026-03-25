@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý HD Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-tour.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Thêm mới</a>
    </div>
</div>

<div class="card js-ajax-list" data-ajax-key="hd-tour-list">
    @include('hd-tour.partials.list')
</div>
@endsection