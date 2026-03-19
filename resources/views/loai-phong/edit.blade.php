@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa loại phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('loai-phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('loai-phong.update', $loaiPhong->MaLoai) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mã loại</label>
                <input type="text" class="form-control" value="{{ $loaiPhong->MaLoai }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên loại*</label>
                <input type="text" name="TenLoai" class="form-control" value="{{ old('TenLoai', $loaiPhong->TenLoai) }}" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu thay đổi</button>
        </form>
    </div>
</div>
@endsection