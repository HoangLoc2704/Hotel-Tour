<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Mã</th>
            <th>Tên dịch vụ</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dichVu as $dv)
        <tr>
            <td>{{ $dv->MaDV }}</td>
            <td>{{ $dv->TenDV }}</td>
            <td>{{ number_format($dv->GiaDV,0,',','.') }}</td>
            <td>{{ $dv->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('dich-vu.show', $dv->MaDV) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                <a href="{{ route('dich-vu.edit', $dv->MaDV) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                <form action="{{ route('dich-vu.destroy', $dv->MaDV) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa dịch vụ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($dichVu->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $dichVu->withQueryString()->links() }}
    </div>
@endif
