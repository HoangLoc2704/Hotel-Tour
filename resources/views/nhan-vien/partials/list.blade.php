<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên nhân viên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Chức vụ</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($nhanVien as $nv)
                <tr>
                    <td>{{ $nv->MaNV }}</td>
                    <td>{{ $nv->TenNV }}</td>
                    <td>{{ $nv->Email }}</td>
                    <td>{{ $nv->SDT }}</td>
                    <td>
                        <span class="badge bg-info">{{ $nv->chucVu->TenCV ?? 'N/A' }}</span>
                    </td>
                    <td>
                        @if ($nv->TrangThai == 1)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Vô hiệu</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('nhan-vien.show', $nv->MaNV) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                        <a href="{{ route('nhan-vien.edit', $nv->MaNV) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <form method="POST" action="{{ route('nhan-vien.destroy', $nv->MaNV) }}" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Không có nhân viên nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($nhanVien->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $nhanVien->appends(request()->query())->links() }}
    </div>
@endif
