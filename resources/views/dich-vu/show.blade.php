@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết dịch vụ</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('dich-vu.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<table class="table">
    <tr>
        <th>Mã dịch vụ</th>
        <td>{{ $dichVu->MaDV }}</td>
    </tr>
    <tr>
        <th>Tên dịch vụ</th>
        <td>{{ $dichVu->TenDV }}</td>
    </tr>
    <tr>
        <th>Giá</th>
        <td>{{ number_format($dichVu->GiaDV,0,',','.') }}</td>
    </tr>
    <tr>
        <th>Trạng thái</th>
        <td>{{ $dichVu->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
    </tr>
</table>
@endsection