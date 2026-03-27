@extends('customer.layout.main')

@section('title', 'Đặt dịch vụ - Khách hàng')

@section('content')
    <main class="container py-5">
        <section id="booking">
            <div class="booking-wrap">
                <div class="section-title-wrap mb-3">
                    <h2>Đặt dịch vụ ngay</h2>
                    <p>Để lại thông tin, chúng tôi sẽ xác nhận lịch đặt với bạn nhanh nhất.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.book-service') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Loại dịch vụ</label>
                        <select id="serviceType" name="loai_dich_vu" class="form-select" required>
                            <option value="">Chọn loại</option>
                            <option value="dich-vu" @selected(old('loai_dich_vu') === 'dich-vu')>Dịch vụ bổ sung</option>
                            <option value="phong" @selected(old('loai_dich_vu') === 'phong')>Phòng nghỉ</option>
                            <option value="tour" @selected(old('loai_dich_vu') === 'tour')>Tour du lịch</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Lựa chọn cụ thể</label>
                        <select id="serviceCode" name="ma_dich_vu" class="form-select" required>
                            <option value="">Chọn dịch vụ trước</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Ngày sử dụng</label>
                        <input type="date" name="ngay_su_dung" class="form-control" value="{{ old('ngay_su_dung') }}" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Số lượng khách</label>
                        <input type="number" min="1" max="50" name="so_luong_khach" class="form-control" value="{{ old('so_luong_khach', 1) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" rows="3" class="form-control" placeholder="Ví dụ: cần check-in sớm, ưu tiên tầng cao...">{{ old('ghi_chu') }}</textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-book">Gửi yêu cầu đặt dịch vụ</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        const serviceMap = {
            'dich-vu': @json($dichVus->map(fn ($item) => ['value' => $item->MaDV, 'label' => $item->TenDV])) ,
            'phong': @json($phongs->map(fn ($item) => ['value' => $item->MaPhong, 'label' => $item->TenPhong])) ,
            'tour': @json($tours->map(fn ($item) => ['value' => $item->MaTour, 'label' => $item->TenTour])) ,
        };

        const oldServiceCode = @json(old('ma_dich_vu'));
        const serviceTypeEl = document.getElementById('serviceType');
        const serviceCodeEl = document.getElementById('serviceCode');

        function updateServiceCodeOptions() {
            const type = serviceTypeEl.value;
            const options = serviceMap[type] || [];
            serviceCodeEl.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = options.length ? 'Chọn gói phù hợp' : 'Không có dữ liệu';
            serviceCodeEl.appendChild(defaultOption);

            options.forEach((item) => {
                const opt = document.createElement('option');
                opt.value = item.value;
                opt.textContent = `${item.label} (${item.value})`;
                if (oldServiceCode && oldServiceCode === item.value) {
                    opt.selected = true;
                }
                serviceCodeEl.appendChild(opt);
            });
        }

        serviceTypeEl.addEventListener('change', updateServiceCodeOptions);
        updateServiceCodeOptions();
    </script>
@endpush
