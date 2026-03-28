@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm HD Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('hd-phong.store') }}">
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
                <label class="form-label">Phòng*</label>
                <select name="MaPhong" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($phongs as $p)
                        <option value="{{ $p->MaPhong }}" {{ old('MaPhong') == $p->MaPhong ? 'selected' : '' }}>{{ $p->TenPhong }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày nhận phòng</label>
                <input type="date" name="NgayNhanPhong" class="form-control" value="{{ old('NgayNhanPhong') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày trả phòng</label>
                <input type="date" name="NgayTraPhong" class="form-control" value="{{ old('NgayTraPhong') }}">
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
            <div class="mb-3">
                <label class="form-label">Thanh toán*</label>
                <select name="ThanhToan" class="form-select" required>
                    <option value="0" {{ old('ThanhToan', 0) == 0 ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="1" {{ old('ThanhToan') == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection