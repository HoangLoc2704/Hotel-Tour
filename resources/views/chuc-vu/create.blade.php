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

<div id="formAlert"></div>

<div class="card">
    <div class="card-body">
        <form id="chucVuCreateForm" method="POST" action="{{ route('chuc-vu.store') }}">
            @csrf
            <div class="mb-3">
                <label for="TenCV" class="form-label">Tên chức vụ <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="TenCV" name="TenCV" value="{{ old('TenCV') }}" required>
                <div class="invalid-feedback" id="TenCVError"></div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
                <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Hủy</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('chucVuCreateForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const form = event.currentTarget;
    const alertBox = document.getElementById('formAlert');
    const tenCVInput = document.getElementById('TenCV');
    const tenCVError = document.getElementById('TenCVError');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    tenCVInput.classList.remove('is-invalid');
    tenCVError.textContent = '';
    alertBox.innerHTML = '';

    const response = await fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            TenCV: tenCVInput.value.trim(),
        }),
    });

    const result = await response.json();

    if (!response.ok) {
        if (response.status === 422 && result.errors?.TenCV) {
            tenCVInput.classList.add('is-invalid');
            tenCVError.textContent = result.errors.TenCV[0];
            return;
        }

        alertBox.innerHTML = `<div class="alert alert-danger">${result.message ?? 'Có lỗi xảy ra.'}</div>`;
        return;
    }

    alertBox.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
    window.setTimeout(() => {
        window.location.href = '{{ route('chuc-vu.index') }}';
    }, 500);
});
</script>
@endsection