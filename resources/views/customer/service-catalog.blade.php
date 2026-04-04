@extends('customer.layout.main')

@section('title', $title . ' - Danh mục dịch vụ')

@section('content')
    @php
        $placeholder = match ($category) {
            'hotel' => 'Nhập tên loại phòng...',
            'tour' => 'Nhập tên tour...',
            default => 'Nhập tên dịch vụ...',
        };
    @endphp

    <main class="container py-5">
        <section>
            <div class="booking-wrap">
                <div class="section-title-wrap mb-3">
                    <h2>{{ $title }}</h2>
                    <p>{{ $subtitle }}</p>
                </div>

                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Từ khóa</label>
                        <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="{{ $placeholder }}">
                    </div>

                    @if ($category === 'hotel')
                        <div class="col-md-4">
                            <label class="form-label">Loại phòng</label>
                            <select name="ma_loai" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach (($roomTypes ?? collect()) as $loai)
                                    <option value="{{ $loai->MaLoai }}" @selected(($filters['ma_loai'] ?? '') == $loai->MaLoai)>{{ $loai->TenLoai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sức chứa tối thiểu</label>
                            <input type="number" name="so_nguoi" min="1" class="form-control" value="{{ $filters['so_nguoi'] ?? '' }}" placeholder="Ví dụ: 2">
                        </div>
                    @elseif ($category === 'tour')
                        <div class="col-md-4">
                            <label class="form-label">Điểm khởi hành</label>
                            <select name="dia_diem" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach (($destinations ?? collect()) as $diaDiem)
                                    <option value="{{ $diaDiem }}" @selected(($filters['dia_diem'] ?? '') === $diaDiem)>{{ $diaDiem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Thời lượng từ</label>
                            <input type="number" name="thoi_luong_tu" min="1" class="form-control" value="{{ $filters['thoi_luong_tu'] ?? '' }}" placeholder="Ngày">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Thời lượng đến</label>
                            <input type="number" name="thoi_luong_den" min="1" class="form-control" value="{{ $filters['thoi_luong_den'] ?? '' }}" placeholder="Ngày">
                        </div>
                    @endif

                    <div class="col-md-2">
                        <label class="form-label">Giá từ</label>
                        <input type="number" name="gia_tu" min="0" class="form-control" value="{{ $filters['gia_tu'] ?? '' }}" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Giá đến</label>
                        <input type="number" name="gia_den" min="0" class="form-control" value="{{ $filters['gia_den'] ?? '' }}" placeholder="1000000">
                    </div>

                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-book">Lọc</button>
                        @if ($category === 'hotel')
                            <a href="{{ route('customer.services.hotel') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                        @elseif ($category === 'tour')
                            <a href="{{ route('customer.services.tour') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                        @else
                            <a href="{{ route('customer.services.addon') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                        @endif
                    </div>
                </form>

                @if ($items->isEmpty())
                    <div class="empty-box">Không có kết quả phù hợp bộ lọc hiện tại.</div>
                @else
                    <div class="row g-4">
                        @foreach ($items as $item)
                            <div class="col-md-6 col-lg-4">
                                @if ($category === 'hotel')
                                    <article class="offer-card h-100">
                                        <div class="offer-image">
                                            <img
                                                src="{{ asset($item->roomImagePath()) }}"
                                                alt="{{ $item->TenLoai }}"
                                                loading="lazy"
                                            >
                                        </div>
                                        <div class="offer-body">
                                            <div class="detail-badge mb-2">Mã loại: {{ $item->MaLoai }}</div>
                                            <h3>{{ $item->TenLoai }}</h3>
                                            <p>{{ (int) ($item->SoLuongNguoi ?? 0) }} người · Loại phòng</p>
                                            <p>{{ \Illuminate\Support\Str::limit($item->MoTa ?: 'Không gian lưu trú thoải mái, phù hợp nghỉ dưỡng ngắn ngày.', 95) }}</p>
                                            <div class="price">{{ number_format($item->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</div>
                                            <a href="{{ route('customer.room-detail', $item->MaLoai) }}" class="detail-link">Xem chi tiết</a>
                                        </div>
                                    </article>
                                @elseif ($category === 'tour')
                                    <article class="offer-card h-100">
                                        <div class="offer-image">
                                            <img
                                                src="{{ asset($item->tourImagePath()) }}"
                                                alt="{{ $item->TenTour }}"
                                                loading="lazy"
                                            >
                                        </div>
                                        <div class="offer-body">
                                            <h3>{{ $item->TenTour }}</h3>
                                            <p>{{ $item->DiaDiemKhoiHanh ?? 'Cập nhật sau' }} · {{ $item->ThoiLuong }} ngày</p>
                                            <p>{{ \Illuminate\Support\Str::limit($item->MoTa ?: 'Tour được thiết kế cân bằng giữa tham quan, nghỉ ngơi và chi phí hợp lý.', 95) }}</p>
                                            <div class="price">Từ {{ number_format($item->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND / người</div>
                                            <a href="{{ route('customer.tour-detail', $item->MaTour) }}" class="detail-link">Xem chi tiết</a>
                                        </div>
                                    </article>
                                @else
                                    <article class="service-card h-100">
                                        <div class="service-card-icon">DV</div>
                                        <h3>{{ $item->TenDV }}</h3>
                                        <p>Dịch vụ bổ sung tiện lợi, dễ kết hợp cùng đặt phòng hoặc tour.</p>
                                        <div class="price">Giá từ {{ number_format($item->GiaDV ?? 0, 0, ',', '.') }} VND</div>
                                        <a href="{{ route('customer.service-detail', $item->MaDV) }}" class="detail-link">Xem chi tiết</a>
                                    </article>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
