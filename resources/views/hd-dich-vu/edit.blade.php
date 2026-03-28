@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa HD Dịch vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hd-dich-vu.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('hd-dich-vu.update', ['maHD' => $hdDichVu->MaHD, 'maDV' => $hdDichVu->MaDV]) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mã HD</label>
                <input type="text" class="form-control" value="{{ $hdDichVu->MaHD }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Mã DV</label>
                <input type="text" class="form-control" value="{{ $hdDichVu->MaDV }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng*</label>
                <input type="number" name="SoLuong" class="form-control" value="{{ old('SoLuong', $hdDichVu->SoLuong) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tổng tiền</label>
                <input type="number" step="0.01" name="TongTien" class="form-control" value="{{ old('TongTien', $hdDichVu->TongTien) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" {{ old('TrangThai', $hdDichVu->TrangThai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai', $hdDichVu->TrangThai) == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Thanh toán*</label>
                <select name="ThanhToan" class="form-select" required>
                    <option value="0" {{ old('ThanhToan', $hdDichVu->ThanhToan) == 0 ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="1" {{ old('ThanhToan', $hdDichVu->ThanhToan) == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection