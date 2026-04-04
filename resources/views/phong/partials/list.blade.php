<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên / mã phòng</th>
                <th>Loại phòng</th>
                <th>Sức chứa</th>
                <th>Giá / đêm</th>
                <th>Ảnh loại phòng</th>
                <th>Ngày đã đặt</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($phong as $p)
            <tr>
                <td>{{ $p->MaPhong }}</td>
                <td>{{ $p->TenPhong }}</td>
                <td>{{ $p->loaiPhong->TenLoai ?? '-' }}</td>
                <td>{{ $p->SoLuongNguoi ?? '-' }} người</td>
                <td>{{ number_format($p->GiaPhong ?? 0, 0, ',', '.') }} VNĐ</td>
                <td>{{ $p->HinhAnh ?: '-' }}</td>
                <td>
                    <div style="position: relative; display: inline-block;">
                        <button
                            type="button"
                            class="room-date-trigger"
                            title="Xem lịch đã đặt"
                            aria-label="Xem lịch đã đặt"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M14 4h-1V2.5a.5.5 0 0 0-1 0V4H4V2.5a.5.5 0 0 0-1 0V4H2a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm1 9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V7h14v6Z"/>
                            </svg>
                        </button>
                        <input
                            type="date"
                            class="room-date-picker"
                            data-booked-dates='@json($p->bookedDates ?? [])'
                            readonly
                            style="position:absolute; opacity:0; pointer-events:none; width:0; height:0; border:0; padding:0;"
                        >
                    </div>
                </td>
                <td>
                    <a href="{{ route('phong.show', $p->MaPhong) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                    <a href="{{ route('phong.edit', $p->MaPhong) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                    <form action="{{ route('phong.destroy', $p->MaPhong) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa phòng?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Không có phòng</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($phong->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $phong->appends(request()->query())->links() }}
    </div>
@endif
