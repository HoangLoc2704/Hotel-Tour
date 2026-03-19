@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('phong.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên phòng*</label>
                <input type="text" name="TenPhong" class="form-control" value="{{ old('TenPhong') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Loại phòng*</label>
                <select name="MaLoai" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach(\App\Models\LoaiPhong::all() as $loai)
                        <option value="{{ $loai->MaLoai }}" {{ old('MaLoai') == $loai->MaLoai ? 'selected' : '' }}>{{ $loai->TenLoai }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá phòng*</label>
                <input type="number" step="0.01" name="GiaPhong" class="form-control" value="{{ old('GiaPhong') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng người*</label>
                <input type="number" name="SoLuongNguoi" class="form-control" value="{{ old('SoLuongNguoi') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="Mota" class="form-control">{{ old('Mota') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh URL</label>
                <input type="text" name="HinhAnh" class="form-control" value="{{ old('HinhAnh') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" selected>Hoạt động</option>
                    <option value="0">Vô hiệu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection