@extends('layout.main')

@section('content')
<h2 class="dashboard-title mb-4">Thêm ảnh tour</h2>

<form action="{{ route('anh-tour.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Tour *</label>
            <select name="MaTour" class="form-select @error('MaTour') is-invalid @enderror">
                <option value="">-- Chọn tour --</option>
                @foreach ($tours as $tour)
                    <option value="{{ $tour->MaTour }}" @selected(old('MaTour') == $tour->MaTour)>
                        {{ $tour->TenTour }} ({{ $tour->MaTour }})
                    </option>
                @endforeach
            </select>
            @error('MaTour')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Chọn ảnh từ máy *</label>
            <input type="file" name="image_file" accept="image/*" class="form-control @error('image_file') is-invalid @enderror">
            <small class="text-muted">Hỗ trợ ảnh JPG, PNG, GIF, WEBP. Hệ thống sẽ tự lưu vào đúng thư mục tour.</small>
            @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('anh-tour.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
