@extends('layout.main')

@section('content')
<h2 class="dashboard-title mb-4">Sửa ảnh dịch vụ</h2>

<form action="{{ route('anh-dich-vu.update', $anhDichVu->MaADV) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Dịch vụ *</label>
            <select name="MaDV" class="form-select @error('MaDV') is-invalid @enderror">
                <option value="">-- Chọn dịch vụ --</option>
                @foreach ($dichVus as $dichVu)
                    <option value="{{ $dichVu->MaDV }}" @selected(old('MaDV', $anhDichVu->MaDV) == $dichVu->MaDV)>
                        {{ $dichVu->TenDV }} ({{ $dichVu->MaDV }})
                    </option>
                @endforeach
            </select>
            @error('MaDV')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label>
            <div class="mb-2">
                <img src="{{ asset($anhDichVu->dichVu?->serviceImagePath($anhDichVu->HinhAnh) ?? ('img/Service/' . $anhDichVu->HinhAnh)) }}" alt="{{ $anhDichVu->HinhAnh }}" style="max-width: 240px; width: 100%; border-radius: 10px; border: 1px solid #ddd;">
            </div>
            <div><code>{{ $anhDichVu->HinhAnh }}</code></div>
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
        <a href="{{ route('anh-dich-vu.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
