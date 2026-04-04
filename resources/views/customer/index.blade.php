@extends('customer.layout.main')

@section('title', 'Khách sạn - Dịch vụ cho khách hàng')

@section('content')
    <section class="hero-section">
        <div id="customerHero" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#customerHero" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#customerHero" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#customerHero" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-slide slide-one">
                        <div class="container">
                            <h1>Trải nghiệm nghỉ dưỡng thanh lịch giữa lòng thành phố</h1>
                            <p>Phòng nghỉ hiện đại, dịch vụ tận tâm, đa dạng gói lựa chọn cho mọi nhu cầu.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide slide-two">
                        <div class="container">
                            <h1>Đặt tour, đặt phòng, đặt dịch vụ chỉ trong 1 bước</h1>
                            <p>Từ du lịch nghỉ dưỡng đến công tác ngắn ngày, chúng tôi đều có gói tối ưu cho bạn.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide slide-three">
                        <div class="container">
                            <h1>Ưu đãi linh hoạt theo nhu cầu theo nhóm</h1>
                            <p>Từ cặp đôi đến gia đình và đoàn khách, dễ dàng tùy chọn và đặt lịch nhanh chóng.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="pb-5">
        <section class="container py-5" id="addon-services">
            <div class="section-title-wrap">
                <h2>Dịch vụ nổi bật</h2>
                <p>Những dịch vụ được khách hàng lựa chọn nhiều nhất trong thời gian gần đây.</p>
            </div>

            <div class="row g-4">
                @forelse ($dichVus as $dichVu)
                    <div class="col-md-6 col-lg-4">
                        <article class="service-card h-100">
                            <div class="service-card-icon">DV</div>
                            <h3>{{ $dichVu->TenDV }}</h3>
                            <p>Giá từ {{ number_format($dichVu->GiaDV ?? 0, 0, ',', '.') }} VND</p>
                            <a href="{{ route('customer.service-detail', $dichVu->MaDV) }}" class="detail-link">Xem chi tiết</a>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Hiện tại chưa có dữ liệu dịch vụ.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="container pb-5" id="hotel-services">
            <div class="section-title-wrap">
                <h2>Phòng đề xuất</h2>
                <p>Không gian thoải mái, đa dạng sức chứa và mức giá linh hoạt.</p>
            </div>

            <div class="row g-4">
                @forelse ($phongs as $phong)
                    <div class="col-md-6 col-lg-4">
                        <article class="offer-card h-100">
                            <div class="offer-image">
                                <img
                                    src="{{ asset('img/Room' . ($phong->HinhAnh ?: 'Don1.jpg')) }}"
                                    alt="{{ $phong->TenPhong }}"
                                    loading="lazy"
                                >
                            </div>
                            <div class="offer-body">
                                <h3>{{ $phong->TenPhong }}</h3>
                                <p>{{ $phong->loaiPhong->TenLoai ?? 'Loại phòng tiêu chuẩn' }}</p>
                                <div class="price">{{ number_format($phong->GiaPhong ?? 0, 0, ',', '.') }} VND / đêm</div>
                                <a href="{{ route('customer.room-detail', $phong->TenPhong) }}" class="detail-link">Xem chi tiết</a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Hiện tại chưa có dữ liệu phòng.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="container pb-5" id="tour-services">
            <div class="section-title-wrap">
                <h2>Tour du lịch</h2>
                <p>Gói tour đa dạng điểm đến, lịch trình gọn gàng, phù hợp nhiều đối tượng.</p>
            </div>

            <div class="row g-4">
                @forelse ($tours as $tour)
                    <div class="col-md-6 col-lg-4">
                        <article class="offer-card h-100">
                            <div class="offer-image">{{ $tour->TenTour }}</div>
                            <div class="offer-body">
                                <h3>{{ $tour->TenTour }}</h3>
                                <p>{{ $tour->DiaDiemKhoiHanh ?? 'Cập nhật tại quận' }} · {{ $tour->ThoiLuong }} ngày</p>
                                <div class="price">Từ {{ number_format($tour->GiaTourNguoiLon ?? 0, 0, ',', '.') }} VND / người</div>
                                <a href="{{ route('customer.tour-detail', $tour->MaTour) }}" class="detail-link">Xem chi tiết</a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-box">Hiện tại chưa có dữ liệu tour.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
