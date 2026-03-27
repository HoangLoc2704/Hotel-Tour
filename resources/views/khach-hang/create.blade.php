@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm khách hàng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('khach-hang.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('khach-hang.store') }}">
            @csrf 
            <div class="mb-3">
                <label class="form-label">Họ và tên*</label>
                <input type="text" name="TenKH" class="form-control" value="{{ old('TenKH') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới tính*</label>
                <select name="GioiTinh" class="form-select" required>
                    <option value="1" {{ old('GioiTinh') == 1 ? 'selected' : '' }}>Nam</option>
                    <option value="0" {{ old('GioiTinh') === '0' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại*</label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="Email" class="form-control" value="{{ old('Email') }}" >
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="MatKhau" class="form-control" >
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