<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mã HD</th>
                <th>Mã Phòng</th>
                <th>Ngày nhận</th>
                <th>Ngày trả</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hdPhong as $hp)
            <tr>
                <td>{{ $hp->MaHD }}</td>
                <td>{{ $hp->MaPhong }}</td>
                <td>{{ $hp->NgayNhanPhong }}</td>
                <td>{{ $hp->NgayTraPhong }}</td>
                <td>{{ number_format($hp->TongTien, 2) }}</td>
                <td>{{ $hp->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
                <td>
                    <a href="{{ route('hd-phong.show', ['maHD' => $hp->MaHD, 'maPhong' => $hp->MaPhong]) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                    <a href="{{ route('hd-phong.edit', ['maHD' => $hp->MaHD, 'maPhong' => $hp->MaPhong]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                    <form method="POST" action="{{ route('hd-phong.destroy', ['maHD' => $hp->MaHD, 'maPhong' => $hp->MaPhong]) }}" style="display:inline;">
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

@if ($hdPhong->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $hdPhong->appends(request()->query())->links() }}
    </div>
@endif
