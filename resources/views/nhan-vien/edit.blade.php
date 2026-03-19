@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa Nhân viên</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('nhan-vien.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('nhan-vien.update', $nhanVien->MaNV) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="TenNV" class="form-label">Tên nhân viên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('TenNV') is-invalid @enderror" id="TenNV" name="TenNV" value="{{ old('TenNV', $nhanVien->TenNV) }}" required>
                    @error('TenNV')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('Email') is-invalid @enderror" id="Email" name="Email" value="{{ old('Email', $nhanVien->Email) }}" required>
                    @error('Email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="SDT" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('SDT') is-invalid @enderror" id="SDT" name="SDT" value="{{ old('SDT', $nhanVien->SDT) }}" maxlength="10" required>
                    @error('SDT')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="NgaySinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('NgaySinh') is-invalid @enderror" id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh', $nhanVien->NgaySinh) }}" required>
                    @error('NgaySinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="GioiTinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                    <select class="form-select @error('GioiTinh') is-invalid @enderror" id="GioiTinh" name="GioiTinh" required>
                        <option value="">--Chọn giới tính--</option>
                        <option value="1" {{ old('GioiTinh', $nhanVien->GioiTinh) == 1 ? 'selected' : '' }}>Nam</option>
                        <option value="0" {{ old('GioiTinh', $nhanVien->GioiTinh) == 0 ? 'selected' : '' }}>Nữ</option>
                    </select>
                    @error('GioiTinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="MaCV" class="form-label">Chức vụ <span class="text-danger">*</span></label>
                    <select class="form-select @error('MaCV') is-invalid @enderror" id="MaCV" name="MaCV" required>
                        <option value="">--Chọn chức vụ--</option>
                        @foreach ($chucVu as $cv)
                            <option value="{{ $cv->MaCV }}" {{ old('MaCV', $nhanVien->MaCV) == $cv->MaCV ? 'selected' : '' }}>{{ $cv->TenCV }}</option>
                        @endforeach
                    </select>
                    @error('MaCV')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="DiaChi" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                <textarea class="form-control @error('DiaChi') is-invalid @enderror" id="DiaChi" name="DiaChi" rows="3" required>{{ old('DiaChi', $nhanVien->DiaChi) }}</textarea>
                @error('DiaChi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="TenTK" class="form-label">Tên tài khoản <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('TenTK') is-invalid @enderror" id="TenTK" name="TenTK" value="{{ old('TenTK', $nhanVien->TenTK) }}" required>
                    @error('TenTK')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="MatKhau" class="form-label">Mật khẩu <span class="text-muted">(Để trống nếu không thay đổi)</span></label>
                    <input type="password" class="form-control @error('MatKhau') is-invalid @enderror" id="MatKhau" name="MatKhau">
                    @error('MatKhau')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="TrangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select @error('TrangThai') is-invalid @enderror" id="TrangThai" name="TrangThai" required>
                    <option value="">--Chọn trạng thái--</option>
                    <option value="1" {{ old('TrangThai', $nhanVien->TrangThai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai', $nhanVien->TrangThai) == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
                @error('TrangThai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Lưu thay đổi
                </button>
                <a href="{{ route('nhan-vien.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
