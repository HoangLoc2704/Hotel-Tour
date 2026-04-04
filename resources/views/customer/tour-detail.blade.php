@extends('customer.layout.main')

@section('title', 'Chi tiết tour - ' . $tour->TenTour)

@section('content')
    <main class="container py-5">
        <section class="detail-shell mb-4">
            <div class="detail-media">{{ $tour->TenTour }}</div>
            <div class="detail-content">
                <div class="detail-badge">Tour du lịch</div>
                <h1 class="detail-title">{{ $tour->TenTour }}</h1>
                <p class="detail-muted">Điểm khởi hành: {{ $tour->DiaDiemKhoiHanh ?: 'Đang cập nhật' }}</p>
                <p class="detail-muted">Thời lượng: {{ $tour->ThoiLuong }} ngày</p>
                <p>{{ $tour->MoTa ?: 'Tour được thiết kế cân bằng giữa trải nghiệm, thời gian nghỉ ngơi và chi phí hợp lý.' }}</p>
                @if (!empty($tour->LichTrinh))
                    <p><strong>Lịch trình:</strong> {{ $tour->LichTrinh }}</p>
                @endif
                <div class="detail-price">Từ {{ number_format($tour->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND / người lớn</div>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <a href="#booking-inline" class="btn btn-book">Đặt ngay tại trang này</a>
                    <a href="{{ route('customer.cart') }}#cart-section" class="btn btn-outline-success">Mở giỏ hàng</a>
                </div>
            </div>
        </section>

        <div id="booking-inline">
            @include('customer.partials.detail-booking-form', [
                'serviceType' => 'tour',
                'serviceCode' => $tour->MaTour,
                'serviceName' => $tour->TenTour,
                'unitPrice' => (float) ($tour->GiaTourNguoiLon ?? 0),
                'customerProfile' => $customerProfile,
                'paymentInfo' => $paymentInfo,
            ])
        </div>

        <section class="mt-4">
            <div class="section-title-wrap">
                <h2>Tour liên quan</h2>
            </div>
            <div class="row g-3">
                @forelse ($relatedTours as $item)
                    <div class="col-md-4">
                        <a class="text-decoration-none" href="{{ route('customer.tour-detail', $item->MaTour) }}">
                            <article class="offer-card h-100">
                                <h3>{{ $item->TenTour }}</h3>
                                <div class="price">Từ {{ number_format($item->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND</div>
                            </article>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Chưa có tour liên quan.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
