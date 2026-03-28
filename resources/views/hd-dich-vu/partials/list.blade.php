<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mã HD</th>
                <th>Mã DV</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hdDichVu as $hdv)
            <tr>
                <td>{{ $hdv->MaHD }}</td>
                <td>{{ $hdv->MaDV }}</td>
                <td>{{ $hdv->SoLuong }}</td>
                <td>{{ number_format($hdv->TongTien, 2) }}</td>
                <td>{{ $hdv->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
                <td>{{ $hdv->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                <td>
                    <a href="{{ route('hd-dich-vu.show', ['maHD' => $hdv->MaHD, 'maDV' => $hdv->MaDV]) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                    <a href="{{ route('hd-dich-vu.edit', ['maHD' => $hdv->MaHD, 'maDV' => $hdv->MaDV]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                    <form method="POST" action="{{ route('hd-dich-vu.destroy', ['maHD' => $hdv->MaHD, 'maDV' => $hdv->MaDV]) }}" style="display:inline;">
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

@if ($hdDichVu->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $hdDichVu->appends(request()->query())->links() }}
    </div>
@endif
