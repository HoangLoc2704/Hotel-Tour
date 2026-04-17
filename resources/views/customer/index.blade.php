@extends('customer.layout.main')

@section('title', 'Khách sạn - Dịch vụ cho khách hàng')

@section('content')
    {{-- Banner Section --}}
    <section class="hero-section">
        <div class="banner-slider-wrapper">
            @include('customer.partials.banner-carousel', ['bannerImages' => $bannerImages ?? []])
        </div>
    </section>

    <main class="pb-5">
        {{-- Services Section --}}
        <section class="container py-5" id="addon-services">
            <div class="section-title-wrap">
                <h2>Dịch vụ nổi bật</h2>
                <p>Những dịch vụ được khách hàng lựa chọn nhiều nhất trong thời gian gần đây.</p>
            </div>
            <div class="row g-4">
                @forelse($dichVus as $item)
                    @include('customer.partials.service-item', ['item' => $item])
                @empty
                    <div class="col-12">
                        <div class="empty-box">Hiện tại chưa có dữ liệu dịch vụ.</div>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Rooms Section --}}
        <section class="container pb-5" id="hotel-services">
            <div class="section-title-wrap">
                <h2>Phòng đề xuất</h2>
                <p>Không gian thoải mái, đa dạng sức chứa và mức giá linh hoạt.</p>
            </div>
            <div class="row g-4">
                @forelse($phongs as $item)
                    @include('customer.partials.offer-item', [
                        'imagePath' => $item->roomImagePath(),
                        'badge' => 'Mã loại: ' . $item->MaLoai,
                        'title' => $item->TenLoai,
                        'meta' => (int)($item->SoLuongNguoi ?? 0) . ' khách · Loại phòng',
                        'description' => \Illuminate\Support\Str::limit($item->MoTa ?: 'Phòng nghỉ thoải mái, phù hợp cho kỳ nghỉ ngắn ngày và nghỉ dưỡng gia đình.', 95),
                        'price' => number_format($item->GiaPhong ?? 0, 0, ',', '.') . ' VND / đêm',
                        'link' => route('customer.room-detail', $item->MaLoai),
                    ])
                @empty
                    <div class="col-12">
                        <div class="empty-box">Hiện tại chưa có dữ liệu phòng.</div>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Tours Section --}}
        <section class="container pb-5" id="tour-services">
            <div class="section-title-wrap">
                <h2>Tour du lịch</h2>
                <p>Gói tour đa dạng điểm đến, lịch trình gọn gàng, phù hợp nhiều đối tượng.</p>
            </div>
            <div class="row g-4">
                @forelse($tours as $item)
                    @include('customer.partials.offer-item', [
                        'imagePath' => $item->tourImagePath(),
                        'badge' => null,
                        'title' => $item->TenTour,
                        'meta' => ($item->DiaDiemKhoiHanh ?? 'Đang cập nhật') . ' · ' . $item->ThoiLuong . ' ngày',
                        'description' => \Illuminate\Support\Str::limit($item->MoTa ?: 'Lịch trình gọn gàng, phù hợp cho gia đình, nhóm bạn và khách du lịch tự do.', 95),
                        'price' => 'Từ ' . number_format($item->GiaTourNguoiLon ?? 0, 0, ',', '.') . ' VND / người',
                        'link' => route('customer.tour-detail', $item->MaTour),
                    ])
                @empty
                    <div class="col-12">
                        <div class="empty-box">Hiện tại chưa có dữ liệu tour.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
