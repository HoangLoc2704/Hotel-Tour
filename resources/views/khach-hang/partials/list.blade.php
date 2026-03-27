<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>SDT</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($khachHang as $kh)
        <tr>
            <td>{{ $kh->MaKH }}</td>
            <td>{{ $kh->TenKH }}</td>
            <td>{{ $kh->SDT }}</td>
            <td>{{ $kh->Email }}</td>
            <td>{{ $kh->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('khach-hang.show', $kh->MaKH) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                <a href="{{ route('khach-hang.edit', $kh->MaKH) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                <form action="{{ route('khach-hang.destroy', $kh->MaKH) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa khách hàng?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($khachHang->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $khachHang->withQueryString()->links() }}
    </div>
@endif
