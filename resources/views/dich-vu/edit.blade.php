@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Sửa dịch vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('dich-vu.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
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
        <form method="POST" action="{{ route('dich-vu.update', $dichVu->MaDV) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mã dịch vụ</label>
                <input type="text" class="form-control" value="{{ $dichVu->MaDV }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên dịch vụ*</label>
                <input type="text" name="TenDV" class="form-control" value="{{ old('TenDV', $dichVu->TenDV) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" step="0.01" name="GiaDV" class="form-control" value="{{ old('GiaDV', $dichVu->GiaDV) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái*</label>
                <select name="TrangThai" class="form-select" required>
                    <option value="1" {{ old('TrangThai', $dichVu->TrangThai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai', $dichVu->TrangThai) == 0 ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
        </form>
    </div>
</div>
@endsection