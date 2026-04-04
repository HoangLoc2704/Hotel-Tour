<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
        <thead class="table-light">
            <tr>
                <th>Mã ảnh</th>
                <th>Tour</th>
                <th>Tên file</th>
                <th>Preview</th>
                <th class="text-center" style="width: 180px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anhTours as $item)
                @php
                    $previewPath = $item->tour
                        ? asset($item->tour->tourImagePath($item->HinhAnh))
                        : asset('img/Tour/' . $item->HinhAnh);
                @endphp
                <tr>
                    <td>{{ $item->MaAT }}</td>
                    <td>
                        <div class="fw-semibold">{{ $item->tour?->TenTour ?? 'Không xác định' }}</div>
                        <div class="text-muted small">Mã tour: {{ $item->MaTour }}</div>
                    </td>
                    <td><code>{{ $item->HinhAnh }}</code></td>
                    <td>
                        <img src="{{ $previewPath }}" alt="{{ $item->HinhAnh }}" style="width: 160px; height: 96px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
                    </td>
                    <td class="text-center">
                        <a href="{{ route('anh-tour.show', $item->MaAT) }}" class="btn btn-sm btn-info text-white">Xem</a>
                        <a href="{{ route('anh-tour.edit', $item->MaAT) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('anh-tour.destroy', $item->MaAT) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa ảnh tour này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Chưa có ảnh tour nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $anhTours->links() }}
</div>
