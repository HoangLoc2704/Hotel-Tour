@extends('customer.layout.main')

@section('title', 'Chi tiết tour - ' . $tour->TenTour)

@section('content')
    <main class="container py-5">
        <section class="detail-shell detail-shell-room mb-4">
            @php
                $galleryImages = $tour->galleryImages->isNotEmpty()
                    ? $tour->galleryImages
                    : collect([$tour->HinhAnh ?: 'TourNuiCam1.jpg']);
                $fallbackImage = $tour->HinhAnh ?: 'TourNuiCam1.jpg';
            @endphp
            <div class="detail-media detail-media-gallery">
                <div id="tourGallery{{ $tour->MaTour }}" class="carousel room-gallery-carousel" data-bs-ride="carousel" data-bs-interval="3600" data-bs-pause="false">
                    <div class="carousel-inner">
                        @foreach ($galleryImages as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img
                                    src="{{ asset($tour->tourImagePath($image)) }}"
                                    alt="{{ $tour->TenTour }} - ảnh {{ $index + 1 }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    onerror="this.onerror=null;this.src='{{ asset($tour->tourImagePath($fallbackImage)) }}';"
                                >
                            </div>
                        @endforeach
                    </div>
                    @if ($galleryImages->count() > 1)
                        <div class="carousel-indicators room-gallery-indicators">
                            @foreach ($galleryImages as $index => $image)
                                <button
                                    type="button"
                                    data-bs-target="#tourGallery{{ $tour->MaTour }}"
                                    data-bs-slide-to="{{ $index }}"
                                    class="{{ $index === 0 ? 'active' : '' }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"
                                ></button>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev room-gallery-control" type="button" data-bs-target="#tourGallery{{ $tour->MaTour }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next room-gallery-control" type="button" data-bs-target="#tourGallery{{ $tour->MaTour }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
            <div class="detail-content">
                <div class="detail-badge">Tour du lịch</div>
                <h1 class="detail-title">{{ $tour->TenTour }}</h1>
                <p class="detail-muted">Điểm khởi hành: {{ $tour->DiaDiemKhoiHanh ?: 'Đang cập nhật' }}</p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light text-dark border">{{ $tour->ThoiLuong }} ngày</span>
                    <span class="badge bg-light text-dark border">Người lớn: {{ number_format($tour->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND</span>
                    <span class="badge bg-light text-dark border">Trẻ em: {{ number_format($tour->GiaTourTreEm ?? 0, 0, ',', '.') }} VND</span>
                </div>
                <p>{{ $tour->MoTa ?: 'Tour được thiết kế cân bằng giữa trải nghiệm, thời gian nghỉ ngơi và chi phí hợp lý.' }}</p>
                @if (!empty($tour->LichTrinh))
                    <p><strong>Lịch trình:</strong> {{ $tour->LichTrinh }}</p>
                @endif
                <div class="detail-price">Từ {{ number_format($tour->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND / người lớn</div>
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
                                <div class="offer-image">
                                    <img
                                        src="{{ asset($item->tourImagePath()) }}"
                                        alt="{{ $item->TenTour }}"
                                        loading="lazy"
                                    >
                                </div>
                                <div class="offer-body">
                                    <h3>{{ $item->TenTour }}</h3>
                                    <p>{{ $item->DiaDiemKhoiHanh ?? 'Đang cập nhật' }}</p>
                                    <div class="price">Từ {{ number_format($item->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND</div>
                                </div>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.getElementById('tourGallery{{ $tour->MaTour }}');

            if (!carousel) {
                return;
            }

            const items = Array.from(carousel.querySelectorAll('.carousel-item'));

            if (items.length === 0) {
                return;
            }

            const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carousel);
            const swipeThreshold = 45;
            let dragStartX = null;
            let dragCurrentX = null;

            const syncGalleryState = () => {
                const activeIndex = items.findIndex((item) => item.classList.contains('active'));
                const safeActiveIndex = activeIndex >= 0 ? activeIndex : 0;

                items.forEach((item, index) => {
                    item.classList.remove('room-gallery-active', 'room-gallery-prev', 'room-gallery-next');

                    if (index === safeActiveIndex) {
                        item.classList.add('room-gallery-active');
                    } else if (index === (safeActiveIndex - 1 + items.length) % items.length) {
                        item.classList.add('room-gallery-prev');
                    } else if (index === (safeActiveIndex + 1) % items.length) {
                        item.classList.add('room-gallery-next');
                    }
                });
            };

            const getClientX = (event) => {
                if (event.touches && event.touches.length > 0) {
                    return event.touches[0].clientX;
                }

                if (event.changedTouches && event.changedTouches.length > 0) {
                    return event.changedTouches[0].clientX;
                }

                return typeof event.clientX === 'number' ? event.clientX : null;
            };

            const startDrag = (event) => {
                dragStartX = getClientX(event);
                dragCurrentX = dragStartX;

                if (dragStartX !== null) {
                    carousel.classList.add('is-dragging');
                }
            };

            const trackDrag = (event) => {
                if (dragStartX === null) {
                    return;
                }

                dragCurrentX = getClientX(event);
            };

            const endDrag = () => {
                if (dragStartX === null || dragCurrentX === null) {
                    dragStartX = null;
                    dragCurrentX = null;
                    carousel.classList.remove('is-dragging');
                    return;
                }

                const deltaX = dragCurrentX - dragStartX;

                if (Math.abs(deltaX) >= swipeThreshold) {
                    if (deltaX < 0) {
                        carouselInstance.next();
                    } else {
                        carouselInstance.prev();
                    }
                }

                dragStartX = null;
                dragCurrentX = null;
                carousel.classList.remove('is-dragging');
            };

            syncGalleryState();
            carousel.addEventListener('slid.bs.carousel', syncGalleryState);
            carousel.addEventListener('touchstart', startDrag, { passive: true });
            carousel.addEventListener('touchmove', trackDrag, { passive: true });
            carousel.addEventListener('touchend', endDrag);
            carousel.addEventListener('mousedown', startDrag);
            carousel.addEventListener('mousemove', trackDrag);
            carousel.addEventListener('mouseup', endDrag);
            carousel.addEventListener('mouseleave', endDrag);
        });
    </script>
@endpush
