@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm HD Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-tour.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('hd-tour.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Hóa đơn*</label>
                <select name="MaHD" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($hoaDons as $hd)
                        <option value="{{ $hd->MaHD }}" {{ old('MaHD') == $hd->MaHD ? 'selected' : '' }}>{{ $hd->MaHD }} - {{ $hd->khachHang->TenKH ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Lịch khởi hành*</label>
                <select name="MaLKH" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($lichKhoiHanhs as $lkh)
                        <option value="{{ $lkh->MaLKH }}" {{ old('MaLKH') == $lkh->MaLKH ? 'selected' : '' }}>{{ $lkh->MaLKH }} - {{ $lkh->tour->TenTour ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Số người lớn</label>
                <input type="number" name="SoNguoiLon" class="form-control" value="{{ old('SoNguoiLon') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Số trẻ em</label>
                <input type="number" name="SoTreEm" class="form-control" value="{{ old('SoTreEm') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Tổng tiền</label>
                <input type="number" step="0.01" name="TongTien" class="form-control" value="{{ old('TongTien') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" {{ old('TrangThai', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai') == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection