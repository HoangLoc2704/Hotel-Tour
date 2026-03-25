<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>Mã</th>
                <th>Tên hướng dẫn viên</th>
                <th>Ngày sinh</th>
                <th>Địa chỉ</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($huongDanVien as $hdv)
            <tr>
                <td>{{ $hdv->MaHDV }}</td>
                <td>{{ $hdv->TenHDV }}</td>
                <td>{{ $hdv->NgaySinh }}</td>
                <td>{{ $hdv->DiaChi }}</td>
                <td>{{ $hdv->SDT }}</td>
                <td>{!! $hdv->TrangThai ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Vô hiệu</span>' !!}</td>
                <td>
                    <a href="{{ route('huong-dan-vien.show', $hdv->MaHDV) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                    <a href="{{ route('huong-dan-vien.edit', $hdv->MaHDV) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                    <form action="{{ route('huong-dan-vien.destroy', $hdv->MaHDV) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa hướng dẫn viên?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Không có dữ liệu</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($huongDanVien->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $huongDanVien->appends(request()->query())->links() }}
    </div>
@endif
