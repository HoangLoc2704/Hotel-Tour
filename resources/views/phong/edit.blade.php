@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="alert alert-warning">
            <strong>Lưu ý:</strong> Giá, sức chứa, ảnh và mô tả thuộc về <strong>loại phòng</strong>, nên ở đây chỉ hiển thị để tham khảo và <strong>không thể chỉnh sửa</strong>. Nếu muốn thay đổi, vui lòng vào mục <strong>Loại phòng</strong>.
        </div>

        <form method="POST" action="{{ route('phong.update', $phong->MaPhong) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Mã phòng</label>
                    <input type="text" class="form-control" value="{{ $phong->MaPhong }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tên / mã phòng*</label>
                    <input type="text" name="TenPhong" class="form-control" value="{{ old('TenPhong', $phong->TenPhong) }}" maxlength="10" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Loại phòng*</label>
                    <select name="MaLoai" id="room-type-select" class="form-select" required>
                        <option value="">-- chọn loại phòng --</option>
                        @foreach($loaiPhong as $loai)
                            <option
                                value="{{ $loai->MaLoai }}"
                                data-gia="{{ $loai->GiaPhong ?? '' }}"
                                data-songuoi="{{ $loai->SoLuongNguoi ?? '' }}"
                                data-hinhanh="{{ $loai->HinhAnh ?? '' }}"
                                data-mota="{{ e($loai->MoTa ?? '') }}"
                                {{ old('MaLoai', $phong->MaLoai) == $loai->MaLoai ? 'selected' : '' }}
                            >
                                {{ $loai->TenLoai }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá phòng / đêm</label>
                    <input type="number" step="0.01" min="0" id="GiaPhong" class="form-control bg-light" value="{{ old('GiaPhong', $phong->GiaPhong) }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lượng người</label>
                    <input type="number" min="1" id="SoLuongNguoi" class="form-control bg-light" value="{{ old('SoLuongNguoi', $phong->SoLuongNguoi) }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tên file hình ảnh</label>
                    <input type="text" id="HinhAnh" class="form-control bg-light" value="{{ old('HinhAnh', $phong->HinhAnh) }}" readonly>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea id="MoTa" class="form-control bg-light" rows="4" readonly>{{ old('MoTa', $phong->MoTa) }}</textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Hình ảnh hiện tại</label>
                    @if(!empty($phong->HinhAnh))
                        <div class="mb-2">
                            <div class="small text-muted">{{ $phong->HinhAnh }}</div>
                            <img src="{{ asset($phong->roomImagePath()) }}" alt="{{ $phong->TenPhong }}" style="max-width: 220px; width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                    @else
                        <div class="text-muted">Chưa có hình ảnh.</div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('room-type-select');
    const giaPhongInput = document.getElementById('GiaPhong');
    const soLuongNguoiInput = document.getElementById('SoLuongNguoi');
    const hinhAnhInput = document.getElementById('HinhAnh');
    const moTaInput = document.getElementById('MoTa');

    if (!typeSelect) {
        return;
    }

    typeSelect.addEventListener('change', function () {
        const selected = typeSelect.options[typeSelect.selectedIndex];

        if (!selected || !selected.value) {
            return;
        }

        giaPhongInput.value = selected.dataset.gia || '';
        soLuongNguoiInput.value = selected.dataset.songuoi || '';
        hinhAnhInput.value = selected.dataset.hinhanh || '';
        moTaInput.value = selected.dataset.mota || '';
    });
});
</script>
@endsection