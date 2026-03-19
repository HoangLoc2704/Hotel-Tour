@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa lịch khởi hành</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('lich-khoi-hanh.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('lich-khoi-hanh.update', $lichKhoiHanh->MaLich) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mã lịch</label>
                <input type="text" class="form-control" value="{{ $lichKhoiHanh->MaLich }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tour*</label>
                <select name="MaTour" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($tours as $t)
                        <option value="{{ $t->MaTour }}" {{ old('MaTour', $lichKhoiHanh->MaTour) == $t->MaTour ? 'selected' : '' }}>{{ $t->TenTour }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày khởi hành*</label>
                <input type="date" name="NgayKhoiHanh" class="form-control" value="{{ old('NgayKhoiHanh', $lichKhoiHanh->NgayKhoiHanh) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Thời gian*</label>
                <input type="time" name="ThoiGian" class="form-control" value="{{ old('ThoiGian', $lichKhoiHanh->ThoiGian) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" {{ old('TrangThai', $lichKhoiHanh->TrangThai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai', $lichKhoiHanh->TrangThai) == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection