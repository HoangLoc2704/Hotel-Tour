@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm lịch khởi hành</h2>
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
        <form method="POST" action="{{ route('lich-khoi-hanh.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tour*</label>
                <select name="MaTour" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($tours as $t)
                        <option value="{{ $t->MaTour }}" {{ old('MaTour') == $t->MaTour ? 'selected' : '' }}>{{ $t->TenTour }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hướng dẫn viên*</label>
                <select name="MaHDV" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($huongDanViens as $hdv)
                        <option value="{{ $hdv->MaHDV }}" {{ old('MaHDV') == $hdv->MaHDV ? 'selected' : '' }}>{{ $hdv->TenHDV }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày khởi hành*</label>
                <input type="date" name="NgayKhoiHanh" class="form-control" value="{{ old('NgayKhoiHanh') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="NgayKetThuc" class="form-control" value="{{ old('NgayKetThuc') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Số chỗ còn lại</label>
                <input type="number" name="SoChoConLai" class="form-control" value="{{ old('SoChoConLai') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Tài xế</label>
                <input type="text" name="TaiXe" class="form-control" value="{{ old('TaiXe') }}" placeholder="Nhập tên tài xế">
            </div>
            <div class="mb-3">
                <label class="form-label">Phương tiện</label>
                <input type="text" name="PhuongTien" class="form-control" value="{{ old('PhuongTien') }}" placeholder="Nhập phương tiện di chuyển">
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection