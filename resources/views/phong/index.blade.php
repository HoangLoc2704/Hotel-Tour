@extends('layout.main')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="dashboard-title">Quản lý Phòng</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('phong.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Thêm phòng</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .room-date-trigger {
        border: 1px solid #ced4da;
        background: #fff;
        color: #0d6efd;
        width: 34px;
        height: 34px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .room-date-trigger:hover {
        background: #f8f9fa;
    }

    .flatpickr-day.booked-day,
    .flatpickr-day.booked-day:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        opacity: 0.65;
        text-decoration: line-through;
    }
</style>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('phong.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên hoặc địa chỉ..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('phong.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên phòng</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Ngày đã đặt</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($phong as $p)
                <tr>
                    <td>{{ $p->MaPhong }}</td>
                    <td>{{ $p->TenPhong }}</td>
                    <td>{{ $p->loaiPhong->TenLoai ?? '' }}</td>
                    <td>{{ number_format($p->GiaPhong,0,',','.') }}</td>
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
                    <td>{!! $p->TrangThai ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Vô hiệu</span>' !!}</td>
                    <td>
                        <a href="{{ route('phong.show', $p->MaPhong) }}" class="btn btn-sm btn-info"><i class="bi bi-eye">Xem</i></a>
                        <a href="{{ route('phong.edit', $p->MaPhong) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil">Sửa</i></a>
                        <form action="{{ route('phong.destroy', $p->MaPhong) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa phòng?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash">Xóa</i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Không có phòng</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $phong->appends(request()->query())->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.room-date-trigger').forEach(function (trigger) {
            var cell = trigger.closest('td');
            if (!cell) {
                return;
            }

            var input = cell.querySelector('.room-date-picker');
            if (!input || !trigger) {
                return;
            }

            var bookedDates = JSON.parse(input.dataset.bookedDates || '[]');
            var bookedSet = new Set(bookedDates);

            var fp = flatpickr(input, {
                dateFormat: 'Y-m-d',
                disable: [function () { return true; }],
                defaultDate: bookedDates.length ? bookedDates[0] : null,
                allowInput: false,
                clickOpens: false,
                positionElement: trigger,
                appendTo: document.body,
                static: false,
                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    var year = dayElem.dateObj.getFullYear();
                    var month = String(dayElem.dateObj.getMonth() + 1).padStart(2, '0');
                    var day = String(dayElem.dateObj.getDate()).padStart(2, '0');
                    var key = year + '-' + month + '-' + day;

                    if (bookedSet.has(key)) {
                        dayElem.classList.add('booked-day');
                    }
                    dayElem.style.pointerEvents = 'none';
                },
                onReady: function (selectedDates, dateStr, instance) {
                    instance.calendarContainer.addEventListener('click', function (event) {
                        event.stopPropagation();
                    });
                }
            });

            trigger.addEventListener('click', function () {
                fp.open();
            });
        });
    });
</script>
@endsection