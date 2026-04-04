@extends('customer.layout.main')

@section('title', 'Chi tiết loại phòng - ' . $phong->TenLoai)

@section('content')
    <main class="container py-5">
        <section class="detail-shell detail-shell-room mb-4">
            @php
                $galleryImages = $phong->galleryImages->isNotEmpty()
                    ? $phong->galleryImages
                    : collect([$phong->HinhAnh ?: 'Don1.jpg']);
                $fallbackImage = $phong->HinhAnh ?: 'Don1.jpg';
            @endphp
            <div class="detail-media detail-media-gallery">
                <div id="roomGallery{{ $phong->MaLoai }}" class="carousel room-gallery-carousel" data-bs-ride="carousel" data-bs-interval="3600" data-bs-pause="false">
                    <div class="carousel-inner">
                        @foreach ($galleryImages as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img
                                    src="{{ asset($phong->roomImagePath($image)) }}"
                                    alt="{{ $phong->TenLoai }} - ảnh {{ $index + 1 }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    onerror="this.onerror=null;this.src='{{ asset($phong->roomImagePath($fallbackImage)) }}';"
                                >
                            </div>
                        @endforeach
                    </div>
                    @if ($galleryImages->count() > 1)
                        <div class="carousel-indicators room-gallery-indicators">
                            @foreach ($galleryImages as $index => $image)
                                <button
                                    type="button"
                                    data-bs-target="#roomGallery{{ $phong->MaLoai }}"
                                    data-bs-slide-to="{{ $index }}"
                                    class="{{ $index === 0 ? 'active' : '' }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"
                                ></button>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev room-gallery-control" type="button" data-bs-target="#roomGallery{{ $phong->MaLoai }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next room-gallery-control" type="button" data-bs-target="#roomGallery{{ $phong->MaLoai }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
            <div class="detail-content">
                <div class="detail-badge">Khách sạn</div>
                <h1 class="detail-title">{{ $phong->TenLoai }}</h1>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light text-dark border">{{ $phong->SoLuongNguoi ?? 'Đang cập nhật' }} khách</span>
                    <span class="badge bg-light text-dark border">{{ number_format($phong->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</span>
                </div>
                <p>{{ $phong->MoTa ?: 'Phòng được thiết kế tối ưu không gian và trải nghiệm lưu trú thoải mái cho mọi nhóm khách.' }}</p>
                <div class="detail-price">{{ number_format($phong->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</div>
            </div>
        </section>

        <div id="booking-inline">
            @include('customer.partials.detail-booking-form', [
                'serviceType' => 'phong',
                'serviceCode' => (string) $phong->MaLoai,
                'serviceName' => $phong->TenLoai,
                'unitPrice' => (float) ($phong->GiaPhong ?? 0),
                'customerProfile' => $customerProfile,
                'paymentInfo' => $paymentInfo,
            ])
        </div>

        <section class="mt-4">
            <div class="section-title-wrap">
                <h2>Loại phòng khác có thể bạn quan tâm</h2>
            </div>
            <div class="row g-3">
                @forelse ($relatedRooms as $room)
                    <div class="col-md-4">
                        <a class="text-decoration-none" href="{{ route('customer.room-detail', $room->MaLoai) }}">
                            <article class="offer-card h-100">
                                <div class="offer-image">
                                    <img
                                        src="{{ asset($room->roomImagePath()) }}"
                                        alt="{{ $room->TenLoai }}"
                                        loading="lazy"
                                    >
                                </div>
                                <div class="offer-body">
                                    <div class="detail-badge mb-2">Mã loại: {{ $room->MaLoai }}</div>
                                    <h3>{{ $room->TenLoai }}</h3>
                                    <p>{{ $room->SoLuongNguoi ?? '-' }} khách</p>
                                    <div class="price">{{ number_format($room->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</div>
                                </div>
                            </article>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Chưa có loại phòng liên quan.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.getElementById('roomGallery{{ $phong->MaLoai }}');

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

            const syncRoomGalleryState = () => {
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

            syncRoomGalleryState();
            carousel.addEventListener('slid.bs.carousel', syncRoomGalleryState);
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
