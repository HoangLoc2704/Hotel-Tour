@extends('customer.layout.main')

@section('title', 'Chi tiết phòng - ' . $phong->TenPhong)

@section('content')
    <main class="container py-5">
        <section class="detail-shell mb-4">
            <div class="detail-media">{{ $phong->TenPhong }}</div>
            <div class="detail-content">
                <div class="detail-badge">Khách sạn</div>
                <h1 class="detail-title">{{ $phong->TenPhong }}</h1>
                <p class="detail-muted">Loại phòng: {{ $phong->loaiPhong->TenLoai ?? 'Tiêu chuẩn' }}</p>
                <p class="detail-muted">Sức chứa: {{ $phong->SoLuongNguoi ?? 'Đang cập nhật' }} khách</p>
                <p>{{ $phong->MoTa ?: 'Phòng được thiết kế tối ưu không gian và trải nghiệm lưu trú thoải mái cho mọi nhóm khách.' }}</p>
                <div class="detail-price">{{ number_format($phong->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</div>
                <a href="{{ route('customer.booking') }}" class="btn btn-book mt-3">Đặt phòng ngay</a>
            </div>
        </section>

        <section class="mt-4">
            <div class="section-title-wrap">
                <h2>Phòng khác có thể bạn quan tâm</h2>
            </div>
            <div class="row g-3">
                @forelse ($relatedRooms as $room)
                    <div class="col-md-4">
                        <a class="text-decoration-none" href="{{ route('customer.room-detail', $room->MaPhong) }}">
                            <article class="offer-card h-100">
                                <h3>{{ $room->TenPhong }}</h3>
                                <div class="price">{{ number_format($room->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</div>
                            </article>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Chưa có phòng liên quan.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
