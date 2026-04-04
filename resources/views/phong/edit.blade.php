@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa Phòng</h2>
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
        <form method="POST" action="{{ route('phong.update', $phong->MaPhong) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mã phòng</label>
                <input type="text" class="form-control" value="{{ $phong->MaPhong }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên phòng*</label>
                <input type="text" name="TenPhong" class="form-control" value="{{ old('TenPhong', $phong->TenPhong) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Loại phòng*</label>
                <select name="MaLoai" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach(\App\Models\LoaiPhong::all() as $loai)
                        <option value="{{ $loai->MaLoai }}" {{ old('MaLoai', $phong->MaLoai) == $loai->MaLoai ? 'selected' : '' }}>{{ $loai->TenLoai }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá phòng*</label>
                <input type="number" step="0.01" name="GiaPhong" class="form-control" value="{{ old('GiaPhong', $phong->GiaPhong) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng người*</label>
                <input type="number" name="SoLuongNguoi" class="form-control" value="{{ old('SoLuongNguoi', $phong->SoLuongNguoi) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="MoTa" class="form-control">{{ old('MoTa', $phong->MoTa) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh</label>
                @if(!empty($phong->HinhAnh))
                    <div class="mb-2">
                        <div class="small text-muted">Ảnh hiện tại: {{ $phong->HinhAnh }}</div>
                        <img src="{{ asset('img/Room' . $phong->HinhAnh) }}" alt="{{ $phong->TenPhong }}" style="max-width: 220px; width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                @endif
                <input type="file" name="HinhAnhFile" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <small class="text-muted">Chọn ảnh mới nếu muốn thay thế ảnh hiện tại.</small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection