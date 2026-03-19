@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết Nhân viên</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('nhan-vien.edit', $nhanVien->MaNV) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Sửa
        </a>
        <a href="{{ route('nhan-vien.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $nhanVien->MaNV }}</p>
                <p><strong>Tên:</strong> {{ $nhanVien->TenNV }}</p>
                <p><strong>Email:</strong> {{ $nhanVien->Email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $nhanVien->SDT }}</p>
                <p><strong>Ngày sinh:</strong> {{ date('d/m/Y', strtotime($nhanVien->NgaySinh)) }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Giới tính:</strong> {{ $nhanVien->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</p>
                <p><strong>Chức vụ:</strong> <span class="badge bg-info">{{ $nhanVien->chucVu->TenCV ?? 'N/A' }}</span></p>
                <p><strong>Tên tài khoản:</strong> {{ $nhanVien->TenTK }}</p>
                <p><strong>Trạng thái:</strong>
                    @if ($nhanVien->TrangThai == 1)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Vô hiệu</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <p><strong>Địa chỉ:</strong> {{ $nhanVien->DiaChi }}</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('nhan-vien.edit', $nhanVien->MaNV) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <form method="POST" action="{{ route('nhan-vien.destroy', $nhanVien->MaNV) }}" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </form>
            <a href="{{ route('nhan-vien.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>
@endsection
