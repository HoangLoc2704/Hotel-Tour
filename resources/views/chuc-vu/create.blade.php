@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Thêm chức vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('chuc-vu.store') }}">
            @csrf
            <div class="mb-3">
                <label for="TenCV" class="form-label">Tên chức vụ <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('TenCV') is-invalid @enderror" id="TenCV" name="TenCV" value="{{ old('TenCV') }}" required>
                @error('TenCV')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
                <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection