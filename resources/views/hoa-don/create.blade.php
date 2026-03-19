@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm hóa đơn</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('hoa-don.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('hoa-don.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Khách hàng*</label>
                <select name="MaKH" class="form-select" required>
                    <option value="">-- chọn --</option>
                    @foreach($khachHang as $kh)
                        <option value="{{ $kh->MaKH }}">{{ $kh->TenKH }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày tạo</label>
                <input type="date" name="NgayTao" class="form-control" value="{{ old('NgayTao', date('Y-m-d')) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Thành tiền</label>
                <input type="number" step="0.01" name="ThanhTien" class="form-control" value="{{ old('ThanhTien') }}">
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