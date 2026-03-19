@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Chi tiết loại phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('loai-phong.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<table class="table">
    <tr>
        <th>Mã loại</th>
        <td>{{ $loaiPhong->MaLoai }}</td>
    </tr>
    <tr>
        <th>Tên loại</th>
        <td>{{ $loaiPhong->TenLoai }}</td>
    </tr>
</table>
@endsection