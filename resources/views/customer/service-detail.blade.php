@extends('customer.layout.main')

@section('title', 'Chi tiết dịch vụ - ' . $dichVu->TenDV)

@section('content')
    <main class="container py-5">
        <section class="detail-shell mb-4">
            <div class="detail-media">{{ $dichVu->TenDV }}</div>
            <div class="detail-content">
                <div class="detail-badge">Dịch vụ đi kèm</div>
                <h1 class="detail-title">{{ $dichVu->TenDV }}</h1>
                <p class="detail-muted">Mã dịch vụ: {{ $dichVu->MaDV }}</p>
                <p>Dịch vụ được cung cấp với tiêu chuẩn vận hành đồng nhất, phù hợp đa dạng nhu cầu của khách lưu trú và khách tham quan.</p>
                <div class="detail-price">{{ number_format($dichVu->GiaDV ?? 0, 0, ',', '.') }} VND</div>
                <a href="{{ route('customer.booking', ['loai_dich_vu' => 'dich-vu', 'ma_dich_vu' => $dichVu->MaDV]) }}" class="btn btn-book mt-3">Đặt dịch vụ ngay</a>
            </div>
        </section>

        <section class="mt-4">
            <div class="section-title-wrap">
                <h2>Dịch vụ liên quan</h2>
            </div>
            <div class="row g-3">
                @forelse ($relatedServices as $item)
                    <div class="col-md-4">
                        <a class="text-decoration-none" href="{{ route('customer.service-detail', $item->MaDV) }}">
                            <article class="service-card h-100">
                                <h3>{{ $item->TenDV }}</h3>
                                <div class="price">{{ number_format($item->GiaDV ?? 0, 0, ',', '.') }} VND</div>
                            </article>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Chưa có dịch vụ liên quan.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
