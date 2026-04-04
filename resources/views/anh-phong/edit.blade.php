@extends('layout.main')

@section('content')
<h2 class="dashboard-title mb-4">Sửa ảnh phòng</h2>

<form action="{{ route('anh-phong.update', $anhPhong->MaAP) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Loại phòng *</label>
            <select name="MaLoai" class="form-select @error('MaLoai') is-invalid @enderror">
                <option value="">-- Chọn loại phòng --</option>
                @foreach ($loaiPhongs as $loaiPhong)
                    <option value="{{ $loaiPhong->MaLoai }}" @selected(old('MaLoai', $anhPhong->MaLoai) == $loaiPhong->MaLoai)>
                        {{ $loaiPhong->TenLoai }} ({{ $loaiPhong->MaLoai }})
                    </option>
                @endforeach
            </select>
            @error('MaLoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label>
            <div class="mb-2">
                <img src="{{ asset($anhPhong->loaiPhong?->roomImagePath($anhPhong->HinhAnh) ?? ('img/Room/' . $anhPhong->HinhAnh)) }}" alt="{{ $anhPhong->HinhAnh }}" style="max-width: 240px; width: 100%; border-radius: 10px; border: 1px solid #ddd;">
            </div>
            <div><code>{{ $anhPhong->HinhAnh }}</code></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Chọn ảnh mới từ máy</label>
            <input type="file" name="image_file" accept="image/*" class="form-control @error('image_file') is-invalid @enderror">
            <small class="text-muted">Để trống nếu bạn muốn giữ nguyên ảnh hiện tại.</small>
            @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('anh-phong.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
