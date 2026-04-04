<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
        <thead class="table-light">
            <tr>
                <th>Mã ảnh</th>
                <th>Loại phòng</th>
                <th>Tên file</th>
                <th>Preview</th>
                <th class="text-center" style="width: 180px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anhPhongs as $item)
                @php
                    $previewPath = $item->loaiPhong
                        ? asset($item->loaiPhong->roomImagePath($item->HinhAnh))
                        : asset('img/Room/' . $item->HinhAnh);
                @endphp
                <tr>
                    <td>{{ $item->MaAP }}</td>
                    <td>
                        <div class="fw-semibold">{{ $item->loaiPhong?->TenLoai ?? 'Không xác định' }}</div>
                        <div class="text-muted small">Mã loại: {{ $item->MaLoai }}</div>
                    </td>
                    <td><code>{{ $item->HinhAnh }}</code></td>
                    <td>
                        <img src="{{ $previewPath }}" alt="{{ $item->HinhAnh }}" style="width: 160px; height: 96px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
                    </td>
                    <td class="text-center">
                        <a href="{{ route('anh-phong.show', $item->MaAP) }}" class="btn btn-sm btn-info text-white">Xem</a>
                        <a href="{{ route('anh-phong.edit', $item->MaAP) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('anh-phong.destroy', $item->MaAP) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa ảnh phòng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Chưa có ảnh phòng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $anhPhongs->links() }}
</div>
