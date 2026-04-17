@if(count($bannerImages) > 0)
    <div class="banner-slider">
        <div class="detail-media detail-media-gallery">
            <div id="customerHero" class="carousel room-gallery-carousel" data-bs-ride="carousel" data-bs-interval="3600" data-bs-pause="false">
                <div class="carousel-inner">
                    @foreach($bannerImages as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active room-gallery-active' : '' }}">
                            <img
                                src="{{ asset('img/Banner/' . $image) }}"
                                alt="Banner {{ $index + 1 }}"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                            >
                        </div>
                    @endforeach
                </div>

                @if(count($bannerImages) > 1)
                    <div class="carousel-indicators room-gallery-indicators">
                        @foreach($bannerImages as $index => $image)
                            <button
                                type="button"
                                data-bs-target="#customerHero"
                                data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Banner {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev room-gallery-control" type="button" data-bs-target="#customerHero" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next room-gallery-control" type="button" data-bs-target="#customerHero" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="banner-slider-placeholder">
        <div class="banner-placeholder-box"></div>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('customerHero');
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
