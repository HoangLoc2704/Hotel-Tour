@extends('layout.main')

@section('content')
    @php
        $gioiTinhLabel = match ((string) ($nhanVien->GioiTinh ?? '')) {
            '1' => 'Nam',
            '0' => 'Nữ',
            default => 'Chưa cập nhật',
        };

        $statusLabel = (int) ($nhanVien->TrangThai ?? 0) === 1 ? 'Đang hoạt động' : 'Đang bị khóa';
        $statusClass = (int) ($nhanVien->TrangThai ?? 0) === 1 ? 'success' : 'danger';
        $selectedGender = (string) old('GioiTinh', isset($nhanVien->GioiTinh) ? (int) $nhanVien->GioiTinh : '');
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Thông tin cá nhân</h2>
            <p class="text-muted mb-0">Bạn có thể cập nhật hồ sơ và đổi mật khẩu ngay tại đây.</p>
        </div>
        <a href="{{ route('admin') }}" class="btn btn-outline-secondary">← Quay lại dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4 d-flex">
            <div class="card shadow-sm border-0 w-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                        <div>
                            <h4 class="mb-1">{{ $nhanVien->TenNV ?? 'Chưa cập nhật' }}</h4>
                            <div class="text-muted">{{ $user['role_name'] ?? 'Chưa cập nhật chức vụ' }}</div>
                        </div>
                        <span class="badge text-bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>

                    <div class="mb-2"><strong>Mã nhân viên:</strong> {{ $nhanVien->MaNV }}</div>
                    <div class="mb-2"><strong>Tên tài khoản:</strong> {{ $nhanVien->TenTK ?: 'Chưa cập nhật' }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $nhanVien->Email ?: 'Chưa cập nhật' }}</div>
                    <div class="mb-2"><strong>Số điện thoại:</strong> {{ $nhanVien->SDT ?: 'Chưa cập nhật' }}</div>
                    <div class="mb-2"><strong>Giới tính:</strong> {{ $gioiTinhLabel }}</div>
                    <div class="mb-2"><strong>Ngày sinh:</strong> {{ $nhanVien->NgaySinh?->format('d/m/Y') ?? 'Chưa cập nhật' }}</div>
                    <div class="mb-0"><strong>Địa chỉ:</strong> {{ $nhanVien->DiaChi ?: 'Chưa cập nhật' }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 d-flex">
            <div class="card shadow-sm border-0 w-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Chỉnh sửa thông tin / đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}" class="row g-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-6">
                            <label for="TenNV" class="form-label">Họ và tên</label>
                            <input type="text" id="TenNV" name="TenNV" class="form-control @error('TenNV') is-invalid @enderror" value="{{ old('TenNV', $nhanVien->TenNV) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="TenTK" class="form-label">Tên tài khoản</label>
                            <input type="text" id="TenTK" name="TenTK" class="form-control @error('TenTK') is-invalid @enderror" value="{{ old('TenTK', $nhanVien->TenTK) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="Email" class="form-label">Email</label>
                            <input type="email" id="Email" name="Email" class="form-control @error('Email') is-invalid @enderror" value="{{ old('Email', $nhanVien->Email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="SDT" class="form-label">Số điện thoại</label>
                            <input type="text" id="SDT" name="SDT" class="form-control @error('SDT') is-invalid @enderror" value="{{ old('SDT', $nhanVien->SDT) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="GioiTinh" class="form-label">Giới tính</label>
                            <select id="GioiTinh" name="GioiTinh" class="form-select @error('GioiTinh') is-invalid @enderror" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="1" {{ $selectedGender === '1' ? 'selected' : '' }}>Nam</option>
                                <option value="0" {{ $selectedGender === '0' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="NgaySinh" class="form-label">Ngày sinh</label>
                            <input type="date" id="NgaySinh" name="NgaySinh" class="form-control @error('NgaySinh') is-invalid @enderror" value="{{ old('NgaySinh', $nhanVien->NgaySinh?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-12">
                            <label for="DiaChi" class="form-label">Địa chỉ</label>
                            <input type="text" id="DiaChi" name="DiaChi" class="form-control @error('DiaChi') is-invalid @enderror" value="{{ old('DiaChi', $nhanVien->DiaChi) }}" placeholder="Nhập địa chỉ hiện tại">
                        </div>

                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="mb-1">Đổi mật khẩu</h6>
                            <p class="text-muted small mb-0">Để trống nếu bạn chưa muốn đổi mật khẩu.</p>
                        </div>

                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                        </div>

                        <div class="col-md-4">
                            <label for="MatKhau" class="form-label">Mật khẩu mới</label>
                            <input type="password" id="MatKhau" name="MatKhau" class="form-control @error('MatKhau') is-invalid @enderror" autocomplete="new-password">
                        </div>

                        <div class="col-md-4">
                            <label for="MatKhau_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" id="MatKhau_confirmation" name="MatKhau_confirmation" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin') }}" class="btn btn-outline-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
