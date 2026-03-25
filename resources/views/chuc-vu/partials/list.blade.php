<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên chức vụ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($chucVu as $cv)
                <tr>
                    <td>{{ $cv->MaCV }}</td>
                    <td>{{ $cv->TenCV }}</td>
                    <td>
                        <a href="{{ route('chuc-vu.edit', $cv->MaCV) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger js-delete-chuc-vu"
                            data-id="{{ $cv->MaCV }}"
                            data-url="{{ route('chuc-vu.destroy', $cv->MaCV) }}"
                        >
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center py-4 text-muted">Không có chức vụ nào</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($chucVu->hasPages())
    <div class="app-pagination app-pagination-card">
        {{ $chucVu->appends(request()->query())->links() }}
    </div>
@endif
