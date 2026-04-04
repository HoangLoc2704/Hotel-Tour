<table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th>Mã</th>
            <th>Tên loại</th>
            <th>Giá / đêm</th>
            <th>Sức chứa</th>
            <th>Ảnh</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @forelse($loaiPhong as $loai)
        <tr>
            <td>{{ $loai->MaLoai }}</td>
            <td>{{ $loai->TenLoai }}</td>
            <td>{{ number_format($loai->GiaPhong ?? 0, 0, ',', '.') }} VNĐ</td>
            <td>{{ $loai->SoLuongNguoi ?? '-' }} người</td>
            <td>{{ $loai->HinhAnh ?: '-' }}</td>
            <td>
                <a href="{{ route('loai-phong.show', $loai->MaLoai) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                <a href="{{ route('loai-phong.edit', $loai->MaLoai) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                <form action="{{ route('loai-phong.destroy', $loai->MaLoai) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa loại phòng?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-4">Không có loại phòng.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if ($loaiPhong->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $loaiPhong->withQueryString()->links() }}
    </div>
@endif
