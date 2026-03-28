<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mã HD</th>
                <th>Mã LKH</th>
                <th>Số người lớn</th>
                <th>Số trẻ em</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hdTour as $ht)
            <tr>
                <td>{{ $ht->MaHD }}</td>
                <td>{{ $ht->MaLKH }}</td>
                <td>{{ $ht->SoNguoiLon }}</td>
                <td>{{ $ht->SoTreEm }}</td>
                <td>{{ number_format($ht->TongTien, 2) }}</td>
                <td>{{ $ht->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
                <td>{{ $ht->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                <td>
                    <a href="{{ route('hd-tour.show', ['maHD' => $ht->MaHD, 'maLKH' => $ht->MaLKH]) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                    <a href="{{ route('hd-tour.edit', ['maHD' => $ht->MaHD, 'maLKH' => $ht->MaLKH]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                    <form method="POST" action="{{ route('hd-tour.destroy', ['maHD' => $ht->MaHD, 'maLKH' => $ht->MaLKH]) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bi bi-trash"></i> Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Không có dữ liệu</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($hdTour->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $hdTour->appends(request()->query())->links() }}
    </div>
@endif
