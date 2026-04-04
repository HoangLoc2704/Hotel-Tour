@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm loại phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('loai-phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('loai-phong.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên loại*</label>
                    <input type="text" name="TenLoai" class="form-control" value="{{ old('TenLoai') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá phòng / đêm</label>
                    <input type="number" step="0.01" min="0" name="GiaPhong" class="form-control" value="{{ old('GiaPhong') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lượng người</label>
                    <input type="number" min="1" name="SoLuongNguoi" class="form-control" value="{{ old('SoLuongNguoi') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Chọn ảnh loại phòng từ máy</label>
                    <input type="file" name="image_file" accept="image/*" class="form-control @error('image_file') is-invalid @enderror">
                    <small class="text-muted">Hệ thống sẽ tự lưu ảnh vào đúng thư mục loại phòng.</small>
                    @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="MoTa" class="form-control" rows="4">{{ old('MoTa') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection