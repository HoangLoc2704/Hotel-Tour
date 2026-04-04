@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm Phòng</h2>
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
        <div class="alert alert-info">
            <strong>Lưu ý:</strong> `MaPhong` được tự tăng theo database. Ở trang này bạn <strong>chỉ chọn loại phòng</strong>; các trường <strong>giá / sức chứa / ảnh / mô tả</strong> bên dưới chỉ để xem tham khảo và <strong>không thể chỉnh sửa</strong> tại đây.
        </div>

        <form method="POST" action="{{ route('phong.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên / mã phòng*</label>
                    <input type="text" name="TenPhong" class="form-control" value="{{ old('TenPhong') }}" maxlength="10" placeholder="Ví dụ: P311" required>
                    <small class="text-muted">Trường này lưu vào cột <code>TenPhong</code>.</small>
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
                                {{ old('MaLoai') == $loai->MaLoai ? 'selected' : '' }}
                            >
                                {{ $loai->TenLoai }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá phòng / đêm</label>
                    <input type="number" step="0.01" min="0" id="GiaPhong" class="form-control bg-light" value="{{ old('GiaPhong') }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lượng người</label>
                    <input type="number" min="1" id="SoLuongNguoi" class="form-control bg-light" value="{{ old('SoLuongNguoi') }}" readonly>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Tên file hình ảnh</label>
                    <input type="text" id="HinhAnh" class="form-control bg-light" value="{{ old('HinhAnh') }}" placeholder="Ảnh sẽ lấy theo loại phòng" readonly>
                    <small class="text-muted">Muốn đổi ảnh, vui lòng vào phần quản lý <strong>Loại phòng</strong>.</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea id="MoTa" class="form-control bg-light" rows="4" readonly>{{ old('MoTa') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
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

    const hydrateFromType = () => {
        const selected = typeSelect.options[typeSelect.selectedIndex];

        if (!selected || !selected.value) {
            return;
        }

        giaPhongInput.value = selected.dataset.gia || giaPhongInput.value;
        soLuongNguoiInput.value = selected.dataset.songuoi || soLuongNguoiInput.value;
        hinhAnhInput.value = selected.dataset.hinhanh || hinhAnhInput.value;
        moTaInput.value = selected.dataset.mota || moTaInput.value;
    };

    typeSelect.addEventListener('change', hydrateFromType);

    if (typeSelect.value && !giaPhongInput.value && !soLuongNguoiInput.value && !hinhAnhInput.value && !moTaInput.value) {
        hydrateFromType();
    }
});
</script>
@endsection