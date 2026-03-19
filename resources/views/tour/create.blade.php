@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('tour.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('tour.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Mã tour*</label>
                <input type="text" name="MaTour" class="form-control" value="{{ old('MaTour') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên tour*</label>
                <input type="text" name="TenTour" class="form-control" value="{{ old('TenTour') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá người lớn*</label>
                <input type="number" step="0.01" name="GiaTourNguoiLon" class="form-control" value="{{ old('GiaTourNguoiLon') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá trẻ em*</label>
                <input type="number" step="0.01" name="GiaTourTreEm" class="form-control" value="{{ old('GiaTourTreEm') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Thời lượng (ngày)*</label>
                <input type="number" name="ThoiLuong" class="form-control" value="{{ old('ThoiLuong') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Địa điểm khởi hành</label>
                <input type="text" name="DiaDiemKhoiHanh" class="form-control" value="{{ old('DiaDiemKhoiHanh') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng khách tối đa</label>
                <input type="number" name="SoLuongKhachToiDa" class="form-control" value="{{ old('SoLuongKhachToiDa') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh URL</label>
                <input type="text" name="HinhAnh" class="form-control" value="{{ old('HinhAnh') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="MoTa" class="form-control">{{ old('MoTa') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lịch trình</label>
                <textarea name="LichTrinh" class="form-control">{{ old('LichTrinh') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" {{ old('TrangThai') == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai') == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection