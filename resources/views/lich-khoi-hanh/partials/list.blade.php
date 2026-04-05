@php
    $formatDate = fn ($value) => filled($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
@endphp

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mã lịch</th>
                <th>Tour</th>
                <th>Ngày khởi hành</th>
                <th>Ngày kết thúc</th>
                <th>Số chỗ còn lại</th>
                <th>Hướng dẫn viên</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lichKhoiHanh as $lkh)
            <tr>
                <td>{{ $lkh->MaLKH }}</td>
                <td>{{ $lkh->tour->TenTour ?? 'N/A' }}</td>
                <td>{{ $formatDate($lkh->NgayKhoiHanh) }}</td>
                <td>{{ $formatDate($lkh->NgayKetThuc) }}</td>
                <td>{{ $lkh->SoChoConLai }}</td>
                <td>{{ $lkh->huongDanVien->TenHDV ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('lich-khoi-hanh.show', $lkh->MaLKH) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                    <a href="{{ route('lich-khoi-hanh.edit', $lkh->MaLKH) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                    <form method="POST" action="{{ route('lich-khoi-hanh.destroy', $lkh->MaLKH) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bi bi-trash"></i> Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Không có dữ liệu</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($lichKhoiHanh->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $lichKhoiHanh->appends(request()->query())->links() }}
    </div>
@endif
