@php
    $formatDate = fn ($value) => filled($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
@endphp

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Mã HD</th>
            <th>Khách hàng</th>
            <th>Ngày tạo</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th>Thanh toán</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hoaDon as $hd)
        <tr>
            <td>{{ $hd->MaHD }}</td>
            <td>{{ $hd->khachHang->TenKH ?? '' }}</td>
            <td>{{ $formatDate($hd->NgayTao) }}</td>
            <td>{{ number_format($hd->ThanhTien, 0, ',', '.') }}</td>
            <td>{{ $hd->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>{{ $hd->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
            <td>
                <a href="{{ route('hoa-don.show', $hd->MaHD) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                <a href="{{ route('hoa-don.edit', $hd->MaHD) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                <a href="{{ route('hoa-don.export-pdf', $hd->MaHD) }}" target="_blank" class="btn btn-sm btn-secondary"><i class="bi bi-file-earmark-pdf"></i> Xuất PDF</a>
                <form action="{{ route('hoa-don.destroy', $hd->MaHD) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa hóa đơn?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($hoaDon->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $hoaDon->withQueryString()->links() }}
    </div>
@endif
