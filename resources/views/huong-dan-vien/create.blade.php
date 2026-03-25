@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm hướng dẫn viên</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('huong-dan-vien.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('huong-dan-vien.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên hướng dẫn viên*</label>
                <input type="text" name="TenHDV" class="form-control" value="{{ old('TenHDV') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ*</label>
                <input type="text" name="DiaChi" class="form-control" value="{{ old('DiaChi') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">SĐT*</label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" {{ old('TrangThai', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai') == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection