<div class="col-md-6 col-lg-4">
    <article class="offer-card h-100">
        <div class="offer-image">
            <img
                src="{{ asset($imagePath) }}"
                alt="{{ $title }}"
                loading="lazy"
            >
        </div>
        <div class="offer-body">
            @if($badge)
                <div class="detail-badge mb-2">{{ $badge }}</div>
            @endif
            <h3>{{ $title }}</h3>
            <p>{{ $meta }}</p>
            <p>{{ $description }}</p>
            <div class="price">{{ $price }}</div>
            <a href="{{ $link }}" class="detail-link">Xem chi tiết</a>
        </div>
    </article>
</div>
