<div class="col-md-6 col-lg-4">
    <article class="service-card h-100">
        <div class="service-card-icon">DV</div>
        <h3>{{ $item->TenDV }}</h3>
        <p>{{ $description ?? 'Dịch vụ tiện ích dành cho khách lưu trú và khách tham quan trong ngày.' }}</p>
        <div class="price">Giá từ {{ number_format($item->GiaDV ?? 0, 0, ',', '.') }} VND</div>
        <a href="{{ route('customer.service-detail', $item->MaDV) }}" class="detail-link">Xem chi tiết</a>
    </article>
</div>
