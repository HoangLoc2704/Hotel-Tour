<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Mã</th>
            <th>Tên Tour</th>
            <th>Giá NL</th>
            <th>Giá TE</th>
            <th>Thời lượng</th>
            <th>Trạng thái</th>
            <th>Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tours as $tour)
        <tr>
            <td>{{ $tour->MaTour }}</td>
            <td>{{ $tour->TenTour }}</td>
            <td>{{ number_format($tour->GiaTourNguoiLon, 0, ',', '.') }}</td>
            <td>{{ number_format($tour->GiaTourTreEm, 0, ',', '.') }}</td>
            <td>{{ $tour->ThoiLuong }} ngày</td>
            <td>{{ $tour->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</td>
            <td>
                <a href="{{ route('tour.edit', $tour->MaTour) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                <form action="{{ route('tour.destroy', $tour->MaTour) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tour này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($tours->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $tours->withQueryString()->links() }}
    </div>
@endif
