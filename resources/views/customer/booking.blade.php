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

                <form id="bookingForm" method="POST" action="{{ route('customer.book-service') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten', $customerProfile->TenKH ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai', $customerProfile->SDT ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customerProfile->Email ?? '') }}">
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

                    <!-- Date fields for room booking -->
                    <div id="roomDateFields" style="display: none;">
                        <div class="col-md-6">
                            <label class="form-label">Ngày nhận phòng</label>
                            <input type="date" id="ngayNhanPhong" name="ngay_nhan_phong" class="form-control" value="{{ old('ngay_nhan_phong') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngày trả phòng</label>
                            <input type="date" id="ngayTraPhong" name="ngay_tra_phong" class="form-control" value="{{ old('ngay_tra_phong') }}">
                        </div>

                        <div class="col-12">
                            <div id="availableRoomsInfo" class="alert alert-info" style="display: none; margin-top: 10px;">
                                <small id="availableRoomsText"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Tour fields -->
                    <div id="tourFields" style="display: none;">
                        <div class="col-12">
                            <label class="form-label">Chọn lịch khởi hành</label>
                            <select id="tourSchedules" name="ma_lich_khoi_hanh" class="form-select">
                                <option value="">Chọn lịch khởi hành</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Số lượng người lớn</label>
                            <input type="number" id="soNguoiLon" name="so_nguoi_lon" min="0" max="50" class="form-control" value="{{ old('so_nguoi_lon', 1) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Số lượng trẻ em</label>
                            <input type="number" id="soTreEm" name="so_tre_em" min="0" max="50" class="form-control" value="{{ old('so_tre_em', 0) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Giá tương ứng</label>
                            <input type="text" id="giaTourDisplay" class="form-control" readonly style="background-color: #f8f9fa;">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Chỗ còn lại</label>
                            <input type="text" id="soChoConLai" class="form-control" readonly style="background-color: #f8f9fa;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngày khởi hành</label>
                            <input type="text" id="ngayKhoiHanhDisplay" class="form-control" readonly style="background-color: #f8f9fa;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="text" id="ngayKetThucDisplay" class="form-control" readonly style="background-color: #f8f9fa;">
                        </div>

                        <div class="col-12">
                            <div id="tourValidationInfo" class="alert alert-info" style="display: none; margin-top: 10px;">
                                <small id="tourValidationText"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Date field for other services -->
                    <div id="otherDateFields">
                        <div class="col-md-2">
                            <label class="form-label">Ngày sử dụng</label>
                            <input type="date" id="ngaySuDung" name="ngay_su_dung" class="form-control" value="{{ old('ngay_su_dung') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Số lượng khách</label>
                            <input type="number" id="soLuongKhach" min="1" max="50" name="so_luong_khach" class="form-control" value="{{ old('so_luong_khach', 1) }}" required>
                        </div>
                    </div>

                    <div class="col-12" id="priceSummary" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Đơn giá</label>
                                <input type="text" id="unitPriceDisplay" class="form-control" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số ngày / số lượng</label>
                                <input type="text" id="quantitySummaryDisplay" class="form-control" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tạm tính</label>
                                <input type="text" id="estimatedTotalDisplay" class="form-control" readonly style="background-color: #f8f9fa; font-weight: 600;">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" rows="3" class="form-control" placeholder="Ví dụ: cần check-in sớm, ưu tiên tầng cao...">{{ old('ghi_chu') }}</textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-book">Gửi yêu cầu đặt dịch vụ</button>
                    </div>
                </form>

                <div class="modal fade" id="paymentQrModal" tabindex="-1" aria-labelledby="paymentQrModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentQrModalLabel">Thanh toán qua mã QR</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-4 align-items-start">
                                    <div class="col-md-5 text-center">
                                        <img id="paymentQrImage" src="" alt="QR thanh toán" class="img-fluid rounded border" style="max-height: 320px;">
                                        <div class="small text-muted mt-2">Quét mã QR bằng app ngân hàng để chuyển khoản</div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="mb-2"><strong>Ngân hàng:</strong> <span id="paymentBankName"></span></div>
                                        <div class="mb-2"><strong>Số tài khoản:</strong> <span id="paymentAccountNo"></span></div>
                                        <div class="mb-2"><strong>Chủ tài khoản:</strong> <span id="paymentAccountName"></span></div>
                                        <div class="mb-2"><strong>Nội dung CK:</strong> <span id="paymentTransferNote"></span></div>
                                        <div class="mb-2"><strong>Số tiền tạm tính:</strong> <span id="paymentAmountText"></span></div>

                                        <hr>
                                        <div class="small text-muted">
                                            <div><strong>Cách setup tài khoản nhận thanh toán:</strong></div>
                                            <div>1. Mở file <code>.env</code> và điền các biến:</div>
                                            <div><code>PAYMENT_BANK_BIN</code>, <code>PAYMENT_BANK_NAME</code>, <code>PAYMENT_ACCOUNT_NO</code>, <code>PAYMENT_ACCOUNT_NAME</code>, <code>PAYMENT_TRANSFER_NOTE_PREFIX</code>, <code>PAYMENT_QR_TEMPLATE</code>.</div>
                                            <div>2. Chạy lại lệnh: <code>php artisan config:clear</code>.</div>
                                            <div>3. Tải lại trang đặt dịch vụ để nhận cấu hình mới.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer flex-column align-items-stretch gap-2">
                                <div id="paymentStatusMsg" class="alert mb-0 d-none" role="alert"></div>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="paymentBackBtn">Quay lại chỉnh sửa</button>
                                    <button type="button" id="confirmPaymentSubmit" class="btn btn-book" disabled>
                                        <span id="confirmPaymentSpinner" class="spinner-border spinner-border-sm me-1 d-none" role="status"></span>
                                        Xác nhận đã chuyển khoản
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        const serviceMap = {
            'dich-vu': @json($dichVuOptions),
            'phong': @json($phongOptions),
            'tour': @json($tourOptions),
        };
        const paymentInfo = @json($paymentInfo);

        const oldServiceCode = @json(old('ma_dich_vu'));
        const oldServiceType = @json(old('loai_dich_vu'));
        const bookingFormEl = document.getElementById('bookingForm');
        const serviceTypeEl = document.getElementById('serviceType');
        const serviceCodeEl = document.getElementById('serviceCode');
        const roomDateFields = document.getElementById('roomDateFields');
        const tourFields = document.getElementById('tourFields');
        const otherDateFields = document.getElementById('otherDateFields');
        const ngayNhanPhongEl = document.getElementById('ngayNhanPhong');
        const ngayTraPhongEl = document.getElementById('ngayTraPhong');
        const ngaySuDungEl = document.getElementById('ngaySuDung');
        const soLuongKhachEl = document.getElementById('soLuongKhach');
        const availableRoomsInfo = document.getElementById('availableRoomsInfo');
        const availableRoomsText = document.getElementById('availableRoomsText');
        const priceSummaryEl = document.getElementById('priceSummary');
        const unitPriceDisplayEl = document.getElementById('unitPriceDisplay');
        const quantitySummaryDisplayEl = document.getElementById('quantitySummaryDisplay');
        const estimatedTotalDisplayEl = document.getElementById('estimatedTotalDisplay');
        
        // Tour elements
        const tourSchedulesEl = document.getElementById('tourSchedules');
        const soNguoiLonEl = document.getElementById('soNguoiLon');
        const soTreEmEl = document.getElementById('soTreEm');
        const giaTourDisplayEl = document.getElementById('giaTourDisplay');
        const soChoConLaiEl = document.getElementById('soChoConLai');
        const ngayKhoiHanhDisplayEl = document.getElementById('ngayKhoiHanhDisplay');
        const ngayKetThucDisplayEl = document.getElementById('ngayKetThucDisplay');
        const tourValidationInfo = document.getElementById('tourValidationInfo');
        const tourValidationText = document.getElementById('tourValidationText');
        const paymentModalEl = document.getElementById('paymentQrModal');
        const paymentQrImageEl = document.getElementById('paymentQrImage');
        const paymentBankNameEl = document.getElementById('paymentBankName');
        const paymentAccountNoEl = document.getElementById('paymentAccountNo');
        const paymentAccountNameEl = document.getElementById('paymentAccountName');
        const paymentTransferNoteEl = document.getElementById('paymentTransferNote');
        const paymentAmountTextEl = document.getElementById('paymentAmountText');
        const confirmPaymentSubmitBtn = document.getElementById('confirmPaymentSubmit');
        const paymentStatusMsgEl = document.getElementById('paymentStatusMsg');
        const confirmPaymentSpinnerEl = document.getElementById('confirmPaymentSpinner');
        const paymentBackBtnEl = document.getElementById('paymentBackBtn');
        const checkPaymentUrl = @json(route('customer.check-payment'));
        let allowDirectSubmit = false;
        const paymentModal = (window.bootstrap && paymentModalEl)
            ? new bootstrap.Modal(paymentModalEl)
            : null;

        function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            }).format(value || 0);
        }

        function resetPriceSummary() {
            unitPriceDisplayEl.value = '';
            quantitySummaryDisplayEl.value = '';
            estimatedTotalDisplayEl.value = '';
            priceSummaryEl.style.display = 'none';
        }

        function getSelectedServiceMeta() {
            const type = serviceTypeEl.value;
            const selectedValue = serviceCodeEl.value;
            const options = serviceMap[type] || [];

            return options.find((item) => String(item.value) === String(selectedValue)) || null;
        }

        function calculateNonTourPrice() {
            const type = serviceTypeEl.value;
            const selected = getSelectedServiceMeta();

            if (!selected || (type !== 'phong' && type !== 'dich-vu')) {
                resetPriceSummary();
                return;
            }

            const unitPrice = parseFloat(selected.price) || 0;

            if (type === 'dich-vu') {
                const soLuong = parseInt(soLuongKhachEl.value, 10) || 1;
                unitPriceDisplayEl.value = formatCurrency(unitPrice);
                quantitySummaryDisplayEl.value = `${soLuong} khách`;
                estimatedTotalDisplayEl.value = formatCurrency(unitPrice * soLuong);
                priceSummaryEl.style.display = 'block';
                return;
            }

            const ngayNhan = ngayNhanPhongEl.value;
            const ngayTra = ngayTraPhongEl.value;

            if (!ngayNhan || !ngayTra) {
                unitPriceDisplayEl.value = formatCurrency(unitPrice);
                quantitySummaryDisplayEl.value = '';
                estimatedTotalDisplayEl.value = '';
                priceSummaryEl.style.display = 'block';
                return;
            }

            const checkIn = new Date(ngayNhan);
            const checkOut = new Date(ngayTra);
            const diffTime = checkOut - checkIn;
            const soDem = Math.max(0, Math.round(diffTime / (1000 * 60 * 60 * 24)));

            unitPriceDisplayEl.value = `${formatCurrency(unitPrice)} / đêm`;
            quantitySummaryDisplayEl.value = soDem > 0 ? `${soDem} đêm` : '';
            estimatedTotalDisplayEl.value = soDem > 0 ? formatCurrency(unitPrice * soDem) : '';
            priceSummaryEl.style.display = 'block';
        }

        function showAvailabilityMessage(message, type = 'info') {
            availableRoomsText.textContent = message;
            availableRoomsInfo.classList.remove('alert-info', 'alert-warning', 'alert-danger', 'alert-success');

            if (type === 'warning') {
                availableRoomsInfo.classList.add('alert-warning');
            } else if (type === 'danger') {
                availableRoomsInfo.classList.add('alert-danger');
            } else if (type === 'success') {
                availableRoomsInfo.classList.add('alert-success');
            } else {
                availableRoomsInfo.classList.add('alert-info');
            }

            availableRoomsInfo.style.display = 'block';
        }

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
                opt.textContent = type === 'phong' ? item.label : `${item.label} (${item.value})`;
                if (oldServiceCode && oldServiceCode === item.value) {
                    opt.selected = true;
                }
                serviceCodeEl.appendChild(opt);
            });

            // Show/hide date fields based on service type
            if (type === 'phong') {
                roomDateFields.style.display = 'block';
                tourFields.style.display = 'none';
                otherDateFields.style.display = 'none';
                ngayNhanPhongEl.required = true;
                ngayTraPhongEl.required = true;
                ngaySuDungEl.required = false;
                soNguoiLonEl.required = false;
                soTreEmEl.required = false;
            } else if (type === 'tour') {
                roomDateFields.style.display = 'none';
                tourFields.style.display = 'block';
                otherDateFields.style.display = 'none';
                ngayNhanPhongEl.required = false;
                ngayTraPhongEl.required = false;
                ngaySuDungEl.required = false;
                soNguoiLonEl.required = true;
                soTreEmEl.required = true;
                tourSchedulesEl.required = true;
            } else if (type === 'dich-vu') {
                roomDateFields.style.display = 'none';
                tourFields.style.display = 'none';
                otherDateFields.style.display = 'block';
                ngayNhanPhongEl.required = false;
                ngayTraPhongEl.required = false;
                ngaySuDungEl.required = true;
                soNguoiLonEl.required = false;
                soTreEmEl.required = false;
            } else {
                roomDateFields.style.display = 'none';
                tourFields.style.display = 'none';
                otherDateFields.style.display = 'block';
            }

            availableRoomsInfo.style.display = 'none';
            tourValidationInfo.style.display = 'none';
            tourSchedulesEl.innerHTML = '<option value="">Chọn lịch khởi hành</option>';
            resetPriceSummary();

            if (type === 'phong') {
                checkAvailableRooms();
                calculateNonTourPrice();
            } else if (type === 'tour') {
                // Clear tour-related fields
                soNguoiLonEl.value = 1;
                soTreEmEl.value = 0;
                giaTourDisplayEl.value = '';
                soChoConLaiEl.value = '';
                ngayKhoiHanhDisplayEl.value = '';
                ngayKetThucDisplayEl.value = '';
            } else if (type === 'dich-vu') {
                calculateNonTourPrice();
            }
        }

        async function checkAvailableRooms() {
            const tenPhong = serviceCodeEl.value;
            const ngayNhanPhong = ngayNhanPhongEl.value;
            const ngayTraPhong = ngayTraPhongEl.value;

            if (!ngayNhanPhong && !ngayTraPhong) {
                showAvailabilityMessage('Vui lòng chọn ngày nhận phòng và ngày trả phòng để kiểm tra phòng trống.', 'info');
                return;
            }

            if (!ngayNhanPhong || !ngayTraPhong) {
                showAvailabilityMessage('Vui lòng chọn đủ cả ngày nhận và ngày trả phòng.', 'warning');
                return;
            }

            if (!tenPhong) {
                showAvailabilityMessage('Vui lòng chọn loại phòng để kiểm tra phòng trống.', 'warning');
                return;
            }

            // Validate date range
            if (new Date(ngayTraPhong) <= new Date(ngayNhanPhong)) {
                showAvailabilityMessage('Ngày trả phòng phải sau ngày nhận phòng!', 'warning');
                return;
            }

            try {
                const params = new URLSearchParams({
                    ten_phong: tenPhong,
                    ngay_nhan: ngayNhanPhong,
                    ngay_tra: ngayTraPhong,
                });

                const checkAvailableUrl = @json(route('customer.check-available-rooms'));

                const response = await fetch(`${checkAvailableUrl}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.available) {
                    showAvailabilityMessage(`Co ${data.room_count} phong kha dung: ${data.available_rooms.join(', ')}`, 'success');
                } else {
                    showAvailabilityMessage('Không có phòng khả dụng trong khoảng thời gian này!', 'danger');
                }
            } catch (error) {
                showAvailabilityMessage('Không thể kiểm tra phòng trống lúc này. Vui lòng thử lại.', 'danger');
                console.error('Error checking rooms:', error);
            }
        }

        serviceTypeEl.addEventListener('change', updateServiceCodeOptions);
        serviceCodeEl.addEventListener('change', () => {
            checkAvailableRooms();
            calculateNonTourPrice();
        });
        ngayNhanPhongEl.addEventListener('change', () => {
            checkAvailableRooms();
            calculateNonTourPrice();
        });
        ngayTraPhongEl.addEventListener('change', () => {
            checkAvailableRooms();
            calculateNonTourPrice();
        });
        ngayNhanPhongEl.addEventListener('input', () => {
            checkAvailableRooms();
            calculateNonTourPrice();
        });
        ngayTraPhongEl.addEventListener('input', () => {
            checkAvailableRooms();
            calculateNonTourPrice();
        });
        soLuongKhachEl.addEventListener('input', calculateNonTourPrice);

        // Tour schedule event handlers
        tourSchedulesEl.addEventListener('change', displayTourScheduleInfo);
        soNguoiLonEl.addEventListener('input', calculateTourPrice);
        soTreEmEl.addEventListener('input', calculateTourPrice);

        // Get tour schedules when tour is selected
        serviceCodeEl.addEventListener('change', async function() {
            if (serviceTypeEl.value === 'tour') {
                await fetchTourSchedules(this.value);
            }
        });

        async function fetchTourSchedules(maTour) {
            if (!maTour) {
                tourSchedulesEl.innerHTML = '<option value="">Chọn lịch khởi hành</option>';
                return;
            }

            try {
                const getTourSchedulesUrl = @json(route('customer.get-tour-schedules'));
                const response = await fetch(`${getTourSchedulesUrl}?ma_tour=${encodeURIComponent(maTour)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                if (!data.success || !data.schedules || data.schedules.length === 0) {
                    tourSchedulesEl.innerHTML = '<option value="">Không có lịch khởi hành nào</option>';
                    showTourValidationMessage('Không có lịch khởi hành nào khả dụng cho tour này.', 'warning');
                    return;
                }

                tourSchedulesEl.innerHTML = '<option value="">Chọn lịch khởi hành</option>';
                data.schedules.forEach((schedule) => {
                    const opt = document.createElement('option');
                    opt.value = schedule.ma_lkh;
                    opt.textContent = `${schedule.ngay_khoi_hanh} - ${schedule.ngay_ket_thuc} (${schedule.so_cho_con_lai} chỗ)`;
                    opt.dataset.ngayKhoiHanh = schedule.ngay_khoi_hanh;
                    opt.dataset.ngayKetThuc = schedule.ngay_ket_thuc;
                    opt.dataset.soChoConLai = schedule.so_cho_con_lai;
                    opt.dataset.giaNguoiLon = schedule.gia_nguoi_lon;
                    opt.dataset.giaTreEm = schedule.gia_tre_em;
                    tourSchedulesEl.appendChild(opt);
                });

                tourValidationInfo.style.display = 'none';
            } catch (error) {
                tourSchedulesEl.innerHTML = '<option value="">Lỗi khi tải lịch khởi hành</option>';
                showTourValidationMessage('Không thể tải lịch khởi hành. Vui lòng thử lại.', 'danger');
                console.error('Error fetching tour schedules:', error);
            }
        }

        function displayTourScheduleInfo() {
            const selectedOption = tourSchedulesEl.options[tourSchedulesEl.selectedIndex];
            
            if (!selectedOption.value) {
                soChoConLaiEl.value = '';
                ngayKhoiHanhDisplayEl.value = '';
                ngayKetThucDisplayEl.value = '';
                giaTourDisplayEl.value = '';
                return;
            }

            soChoConLaiEl.value = selectedOption.dataset.soChoConLai + ' chỗ';
            ngayKhoiHanhDisplayEl.value = selectedOption.dataset.ngayKhoiHanh;
            ngayKetThucDisplayEl.value = selectedOption.dataset.ngayKetThuc;

            calculateTourPrice();
        }

        function calculateTourPrice() {
            const selectedOption = tourSchedulesEl.options[tourSchedulesEl.selectedIndex];
            
            if (!selectedOption.value) {
                giaTourDisplayEl.value = '';
                return;
            }

            const soNguoiLon = parseInt(soNguoiLonEl.value) || 0;
            const soTreEm = parseInt(soTreEmEl.value) || 0;
            const giaNguoiLon = parseFloat(selectedOption.dataset.giaNguoiLon) || 0;
            const giaTreEm = parseFloat(selectedOption.dataset.giaTreEm) || 0;
            const soChoConLai = parseInt(selectedOption.dataset.soChoConLai) || 0;

            const totalNguoi = soNguoiLon + soTreEm;

            // Validate number of people
            if (totalNguoi > soChoConLai) {
                showTourValidationMessage(`Vượt quá số chỗ còn lại (${soChoConLai} chỗ). Vui lòng giảm số lượng người.`, 'warning');
            } else {
                tourValidationInfo.style.display = 'none';
            }

            const tongGia = (soNguoiLon * giaNguoiLon) + (soTreEm * giaTreEm);
            
            const formatter = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            });

            giaTourDisplayEl.value = formatter.format(tongGia);
        }

        function showTourValidationMessage(message, type = 'info') {
            tourValidationText.textContent = message;
            tourValidationInfo.classList.remove('alert-info', 'alert-warning', 'alert-danger', 'alert-success');

            if (type === 'warning') {
                tourValidationInfo.classList.add('alert-warning');
            } else if (type === 'danger') {
                tourValidationInfo.classList.add('alert-danger');
            } else if (type === 'success') {
                tourValidationInfo.classList.add('alert-success');
            } else {
                tourValidationInfo.classList.add('alert-info');
            }

            tourValidationInfo.style.display = 'block';
        }

        function calculateEstimatedAmount() {
            const type = serviceTypeEl.value;
            const selected = getSelectedServiceMeta();

            if (!selected) {
                return 0;
            }

            if (type === 'dich-vu') {
                const soLuong = parseInt(soLuongKhachEl.value, 10) || 1;
                const unitPrice = parseFloat(selected.price) || 0;
                return unitPrice * soLuong;
            }

            if (type === 'phong') {
                const unitPrice = parseFloat(selected.price) || 0;
                const ngayNhan = ngayNhanPhongEl.value;
                const ngayTra = ngayTraPhongEl.value;
                if (!ngayNhan || !ngayTra) {
                    return 0;
                }

                const checkIn = new Date(ngayNhan);
                const checkOut = new Date(ngayTra);
                const soDem = Math.max(0, Math.round((checkOut - checkIn) / (1000 * 60 * 60 * 24)));
                return soDem > 0 ? unitPrice * soDem : 0;
            }

            if (type === 'tour') {
                const selectedSchedule = tourSchedulesEl.options[tourSchedulesEl.selectedIndex];
                if (!selectedSchedule || !selectedSchedule.value) {
                    return 0;
                }

                const soNguoiLon = parseInt(soNguoiLonEl.value, 10) || 0;
                const soTreEm = parseInt(soTreEmEl.value, 10) || 0;
                const giaNguoiLon = parseFloat(selectedSchedule.dataset.giaNguoiLon) || 0;
                const giaTreEm = parseFloat(selectedSchedule.dataset.giaTreEm) || 0;
                return (soNguoiLon * giaNguoiLon) + (soTreEm * giaTreEm);
            }

            return 0;
        }

        function buildTransferNote() {
            const prefix = (paymentInfo.transfer_note_prefix || 'DATDICHVU').toUpperCase();
            const type = (serviceTypeEl.value || 'NA').toUpperCase();
            const code = (serviceCodeEl.value || 'NA').toString().toUpperCase();
            return `${prefix}-${type}-${code}`;
        }

        function buildQrUrl(amount, transferNote) {
            const bankBin = paymentInfo.bank_bin || '';
            const accountNo = paymentInfo.account_no || '';
            const accountName = paymentInfo.account_name || '';
            const qrTemplate = paymentInfo.qr_template || 'compact2';

            if (!bankBin || !accountNo) {
                return '';
            }

            const params = new URLSearchParams({
                amount: String(Math.max(0, Math.round(amount))),
                addInfo: transferNote,
                accountName,
            });

            return `https://img.vietqr.io/image/${encodeURIComponent(bankBin)}-${encodeURIComponent(accountNo)}-${encodeURIComponent(qrTemplate)}.png?${params.toString()}`;
        }

        function openPaymentModalBeforeSubmit() {
            if (!paymentModal) {
                allowDirectSubmit = true;
                bookingFormEl.submit();
                return;
            }

            const amount = calculateEstimatedAmount();
            const transferNote = buildTransferNote();
            const qrUrl = buildQrUrl(amount, transferNote);

            paymentBankNameEl.textContent = paymentInfo.bank_name || 'Chưa cấu hình';
            paymentAccountNoEl.textContent = paymentInfo.account_no || 'Chưa cấu hình';
            paymentAccountNameEl.textContent = paymentInfo.account_name || 'Chưa cấu hình';
            paymentTransferNoteEl.textContent = transferNote;
            paymentAmountTextEl.textContent = formatCurrency(amount);

            if (qrUrl) {
                paymentQrImageEl.src = qrUrl;
                paymentQrImageEl.style.display = 'inline-block';
            } else {
                paymentQrImageEl.removeAttribute('src');
                paymentQrImageEl.style.display = 'none';
            }

            // Reset status khi mở modal mới
            confirmPaymentSubmitBtn.disabled = false;
            paymentStatusMsgEl.className = 'alert mb-0 d-none';
            paymentStatusMsgEl.textContent = '';

            paymentModal.show();
        }

        function setPaymentStatus(type, message) {
            paymentStatusMsgEl.className = 'alert mb-0 alert-' + type;
            paymentStatusMsgEl.textContent = message;
        }

        async function verifyPayment() {
            const transferNote = paymentTransferNoteEl.textContent.trim();
            if (!transferNote) {
                setPaymentStatus('warning', 'Không tìm thấy nội dung chuyển khoản để kiểm tra.');
                return;
            }

            // Loading state
            confirmPaymentSubmitBtn.disabled = true;
            confirmPaymentSpinnerEl.classList.remove('d-none');
            paymentBackBtnEl.disabled = true;
            setPaymentStatus('info', 'Đang kiểm tra biến động số dư tài khoản...');

            try {
                const params = new URLSearchParams({ transfer_note: transferNote });
                const resp = await fetch(`${checkPaymentUrl}?${params.toString()}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (!resp.ok) {
                    throw new Error('HTTP ' + resp.status);
                }

                const data = await resp.json();

                if (data.paid) {
                    setPaymentStatus('success', data.message || 'Xác nhận thanh toán thành công! Đang gửi yêu cầu...');
                    confirmPaymentSpinnerEl.classList.add('d-none');
                    // Tự submit sau 1.5 giây để người dùng thấy thông báo
                    setTimeout(() => {
                        allowDirectSubmit = true;
                        bookingFormEl.submit();
                    }, 1500);
                } else {
                    setPaymentStatus('danger', data.message || 'Chưa tìm thấy biến động số dư. Vui lòng thử lại sau vài giây.');
                    confirmPaymentSubmitBtn.disabled = false;
                    confirmPaymentSpinnerEl.classList.add('d-none');
                    paymentBackBtnEl.disabled = false;
                }
            } catch (err) {
                setPaymentStatus('danger', 'Lỗi kết nối khi kiểm tra thanh toán. Vui lòng thử lại.');
                confirmPaymentSubmitBtn.disabled = false;
                confirmPaymentSpinnerEl.classList.add('d-none');
                paymentBackBtnEl.disabled = false;
                console.error('checkPaymentStatus error:', err);
            }
        }

        // Initialize on page load if old values exist
        if (oldServiceType) {
            updateServiceCodeOptions();
            if (oldServiceType === 'phong') {
                setTimeout(checkAvailableRooms, 100);
            }
            if (oldServiceType === 'phong' || oldServiceType === 'dich-vu') {
                setTimeout(calculateNonTourPrice, 100);
            }
        }

        bookingFormEl.addEventListener('submit', (event) => {
            if (allowDirectSubmit) {
                return;
            }

            event.preventDefault();
            openPaymentModalBeforeSubmit();
        });

        confirmPaymentSubmitBtn.addEventListener('click', verifyPayment);
    </script>
@endpush
