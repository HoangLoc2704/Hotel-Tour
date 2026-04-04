@extends('layout.main')

@section('content')
<h2 class="dashboard-title mb-4">Thêm ảnh dịch vụ</h2>

<form action="{{ route('anh-dich-vu.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Dịch vụ *</label>
            <select name="MaDV" class="form-select @error('MaDV') is-invalid @enderror">
                <option value="">-- Chọn dịch vụ --</option>
                @foreach ($dichVus as $dichVu)
                    <option value="{{ $dichVu->MaDV }}" @selected(old('MaDV') == $dichVu->MaDV)>
                        {{ $dichVu->TenDV }} ({{ $dichVu->MaDV }})
                    </option>
                @endforeach
            </select>
            @error('MaDV')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Chọn ảnh từ máy *</label>
            <input type="file" name="image_file" accept="image/*" class="form-control @error('image_file') is-invalid @enderror">
            <small class="text-muted">Hỗ trợ ảnh JPG, PNG, GIF, WEBP. Hệ thống sẽ tự lưu vào đúng thư mục dịch vụ.</small>
            @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('anh-dich-vu.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
