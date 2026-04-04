@extends('layout.main')

@section('content')
<h2 class="dashboard-title mb-4">Sửa ảnh tour</h2>

<form action="{{ route('anh-tour.update', $anhTour->MaAT) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Tour *</label>
            <select name="MaTour" class="form-select @error('MaTour') is-invalid @enderror">
                <option value="">-- Chọn tour --</option>
                @foreach ($tours as $tour)
                    <option value="{{ $tour->MaTour }}" @selected(old('MaTour', $anhTour->MaTour) == $tour->MaTour)>
                        {{ $tour->TenTour }} ({{ $tour->MaTour }})
                    </option>
                @endforeach
            </select>
            @error('MaTour')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label>
            <div class="mb-2">
                <img src="{{ asset($anhTour->tour?->tourImagePath($anhTour->HinhAnh) ?? ('img/Tour/' . $anhTour->HinhAnh)) }}" alt="{{ $anhTour->HinhAnh }}" style="max-width: 240px; width: 100%; border-radius: 10px; border: 1px solid #ddd;">
            </div>
            <div><code>{{ $anhTour->HinhAnh }}</code></div>
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
        <a href="{{ route('anh-tour.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
