@php
    $typeLabels = [
        'dich-vu' => 'Dịch vụ đi kèm',
        'phong' => 'Phòng nghỉ',
        'tour' => 'Tour du lịch',
    ];
    $typeLabel = $typeLabels[$serviceType] ?? 'Dịch vụ';
    $instanceKey = \Illuminate\Support\Str::slug($serviceType . '-' . $serviceCode);
    $formId = 'detailBookingForm_' . $instanceKey;
    $modalId = 'paymentQrModal_' . $instanceKey;
    $today = now()->toDateString();
@endphp

<section class="booking-wrap mt-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2 mb-3">
        <div>
            <h2 class="mb-1">Đặt {{ mb_strtolower($typeLabel) }} ngay</h2>
            <p class="mb-0 text-muted">Chọn thông tin phù hợp rồi thêm vào giỏ hàng, thanh toán online hoặc thanh toán tại quầy.</p>
        </div>
        <a href="{{ route('customer.cart') }}#cart-section" class="btn btn-outline-success">
            Xem giỏ hàng (<span data-cart-count-text>{{ count(session('customer_cart', [])) }}</span>)
        </a>
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

    @unless (session()->has('customer_user_id'))
        <div class="alert alert-info">
            Bạn có thể thêm vào giỏ hàng mà không cần đăng nhập. Khi thanh toán, vui lòng nhập đầy đủ họ tên, số điện thoại và email.
        </div>
    @endunless

    <form id="{{ $formId }}" method="POST" action="{{ route('customer.book-service') }}" class="row g-3">
        @csrf
        <input type="hidden" name="loai_dich_vu" value="{{ $serviceType }}">
        <input type="hidden" name="ma_dich_vu" value="{{ $serviceCode }}">
        <input type="hidden" name="booking_action" value="{{ old('booking_action', 'book_now') }}" data-role="booking-action">
        <input type="hidden" name="payment_method" value="{{ old('payment_method', 'online') }}" data-role="payment-method">
        <input type="hidden" name="payment_verified" value="{{ old('payment_verified', 0) }}" data-role="payment-verified">
        <input type="hidden" name="payment_transfer_note" value="{{ old('payment_transfer_note', '') }}" data-role="payment-transfer-note">

        <div class="col-md-6">
            <label class="form-label">Họ tên</label>
            <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten', $customerProfile->TenKH ?? session('customer_guest_profile.ho_ten', '')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai', $customerProfile->SDT ?? session('customer_guest_profile.so_dien_thoai', '')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customerProfile->Email ?? session('customer_guest_profile.email', '')) }}" required>
        </div>

        <div class="col-12">
            <div class="booking-inline-head">
                <div>
                    <div class="detail-badge">{{ $typeLabel }}</div>
                    <div class="fw-semibold mt-2">{{ $serviceName }}</div>
                </div>
                <div class="detail-price">{{ number_format($unitPrice ?? 0, 0, ',', '.') }} VND{{ $serviceType === 'phong' ? ' / đêm' : '' }}</div>
            </div>
        </div>

        @if ($serviceType === 'dich-vu')
            <div class="col-md-4">
                <label class="form-label">Ngày sử dụng</label>
                <input type="date" name="ngay_su_dung" class="form-control" min="{{ $today }}" value="{{ old('ngay_su_dung', $today) }}" data-role="usage-date" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Số lượng</label>
                <input type="number" name="so_luong_khach" class="form-control" min="1" max="50" value="{{ old('so_luong_khach', 1) }}" data-role="quantity" required>
            </div>
        @elseif ($serviceType === 'phong')
            <div class="col-md-4">
                <label class="form-label">Ngày nhận phòng</label>
                <input type="date" name="ngay_nhan_phong" class="form-control" min="{{ $today }}" value="{{ old('ngay_nhan_phong', $today) }}" data-role="checkin" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ngày trả phòng</label>
                <input type="date" name="ngay_tra_phong" class="form-control" min="{{ $today }}" value="{{ old('ngay_tra_phong', now()->addDay()->toDateString()) }}" data-role="checkout" required>
            </div>
            <div class="col-12">
                <div class="alert alert-info mb-0 d-none" data-role="room-status"></div>
            </div>
        @elseif ($serviceType === 'tour')
            <div class="col-md-6">
                <label class="form-label">Lịch khởi hành</label>
                <select name="ma_lich_khoi_hanh" class="form-select" data-role="schedule" required>
                    <option value="">Đang tải lịch khởi hành...</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Người lớn</label>
                <input type="number" name="so_nguoi_lon" class="form-control" min="0" max="50" value="{{ old('so_nguoi_lon', 1) }}" data-role="adult-count" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trẻ em</label>
                <input type="number" name="so_tre_em" class="form-control" min="0" max="50" value="{{ old('so_tre_em', 0) }}" data-role="child-count" required>
            </div>
            <div class="col-12">
                <div class="alert alert-info mb-0 d-none" data-role="tour-status"></div>
            </div>
        @endif

        <div class="col-12">
            <label class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" rows="3" class="form-control" placeholder="Yêu cầu thêm nếu có...">{{ old('ghi_chu') }}</textarea>
        </div>

        <div class="col-12">
            <div class="alert d-none mb-0" data-role="ajax-message" role="alert"></div>
        </div>

        <div class="col-12">
            <div class="booking-summary-box">
                <div>
                    <div class="summary-label">Tạm tính</div>
                    <div class="summary-value" data-role="estimated-total">{{ number_format($unitPrice ?? 0, 0, ',', '.') }} VND</div>
                </div>
                <div class="summary-meta" data-role="summary-meta">{{ $serviceType === 'tour' ? 'Chọn lịch và số khách phù hợp' : 'Điều chỉnh lựa chọn để cập nhật giá' }}</div>
            </div>
        </div>

        <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
            <button type="submit" class="btn btn-outline-success" data-role="add-to-cart">Thêm vào giỏ hàng</button>
            <button type="submit" class="btn btn-outline-secondary" data-role="pay-counter">Thanh toán tại quầy</button>
            <button type="submit" class="btn btn-book" data-role="pay-online">Đặt & thanh toán online</button>
        </div>
    </form>

    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thanh toán qua mã QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-5 text-center">
                            <img src="" alt="QR thanh toán" class="img-fluid rounded border" style="max-height: 320px;" data-role="qr-image">
                            <div class="small text-muted mt-2">Quét mã bằng app ngân hàng để thanh toán ngay</div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-2"><strong>Ngân hàng:</strong> <span data-role="bank-name"></span></div>
                            <div class="mb-2"><strong>Số tài khoản:</strong> <span data-role="account-no"></span></div>
                            <div class="mb-2"><strong>Chủ tài khoản:</strong> <span data-role="account-name"></span></div>
                            <div class="mb-2"><strong>Nội dung CK:</strong> <span data-role="transfer-note"></span></div>
                            <div class="mb-2"><strong>Số tiền tạm tính:</strong> <span data-role="payment-amount"></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-column align-items-stretch gap-2">
                    <div class="alert mb-0 d-none" data-role="payment-status"></div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Quay lại</button>
                        <button type="button" class="btn btn-book" data-role="confirm-payment">Xác nhận đã chuyển khoản</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById(@json($formId));
            if (!form) {
                return;
            }

            const paymentInfo = @json($paymentInfo);
            const serviceType = @json($serviceType);
            const serviceCode = @json($serviceCode);
            const unitPrice = Number(@json((float) ($unitPrice ?? 0))) || 0;
            const oldSchedule = @json(old('ma_lich_khoi_hanh', ''));
            const bookingActionInput = form.querySelector('[data-role="booking-action"]');
            const paymentMethodInput = form.querySelector('[data-role="payment-method"]');
            const paymentVerifiedInput = form.querySelector('[data-role="payment-verified"]');
            const paymentTransferNoteInput = form.querySelector('[data-role="payment-transfer-note"]');
            const ajaxMessageEl = form.querySelector('[data-role="ajax-message"]');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const estimatedTotalEl = form.querySelector('[data-role="estimated-total"]');
            const summaryMetaEl = form.querySelector('[data-role="summary-meta"]');
            const addToCartBtn = form.querySelector('[data-role="add-to-cart"]');
            const payCounterBtn = form.querySelector('[data-role="pay-counter"]');
            const payOnlineBtn = form.querySelector('[data-role="pay-online"]');
            const roomStatusEl = form.querySelector('[data-role="room-status"]');
            const tourStatusEl = form.querySelector('[data-role="tour-status"]');
            const modalEl = document.getElementById(@json($modalId));
            const qrImageEl = modalEl?.querySelector('[data-role="qr-image"]');
            const bankNameEl = modalEl?.querySelector('[data-role="bank-name"]');
            const accountNoEl = modalEl?.querySelector('[data-role="account-no"]');
            const accountNameEl = modalEl?.querySelector('[data-role="account-name"]');
            const transferNoteEl = modalEl?.querySelector('[data-role="transfer-note"]');
            const paymentAmountEl = modalEl?.querySelector('[data-role="payment-amount"]');
            const paymentStatusEl = modalEl?.querySelector('[data-role="payment-status"]');
            const confirmPaymentBtn = modalEl?.querySelector('[data-role="confirm-payment"]');
            const paymentModal = (window.bootstrap && modalEl) ? new bootstrap.Modal(modalEl) : null;
            const roomCheckUrl = @json(route('customer.check-available-rooms'));
            const tourScheduleUrl = @json(route('customer.get-tour-schedules'));
            const verifyPaymentUrl = @json(route('payment.sepay.webhook'));
            let allowDirectSubmit = false;

            const formatCurrency = (value) => new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            }).format(Number(value || 0));

            const setStatus = (element, type, message) => {
                if (!element) {
                    return;
                }
                element.className = 'alert mb-0 alert-' + type;
                element.textContent = message;
                element.classList.remove('d-none');
            };

            const clearStatus = (element) => {
                if (!element) {
                    return;
                }
                element.className = 'alert mb-0 d-none';
                element.textContent = '';
            };

            const setAjaxMessage = (type, message) => {
                if (!ajaxMessageEl) {
                    return;
                }
                ajaxMessageEl.className = `alert alert-${type} mb-0`;
                ajaxMessageEl.textContent = message;
            };

            const clearAjaxMessage = () => {
                if (!ajaxMessageEl) {
                    return;
                }
                ajaxMessageEl.className = 'alert d-none mb-0';
                ajaxMessageEl.textContent = '';
            };

            const buildAjaxErrorMessage = (error, fallbackMessage) => {
                if (error && typeof error === 'object' && error.errors) {
                    return Object.values(error.errors).flat().join(' ');
                }

                if (error && typeof error.message === 'string' && error.message.trim() !== '') {
                    return error.message;
                }

                return fallbackMessage;
            };

            const submitAddToCartAjax = async () => {
                if (!form.reportValidity()) {
                    return;
                }

                bookingActionInput.value = 'add_to_cart';
                paymentMethodInput.value = 'counter';
                paymentVerifiedInput.value = '0';
                paymentTransferNoteInput.value = '';
                clearAjaxMessage();

                const originalLabel = addToCartBtn.textContent;
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Đang thêm...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: new FormData(form),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw data;
                    }

                    setAjaxMessage('success', data.message || 'Đã thêm dịch vụ vào giỏ hàng.');
                    if (typeof window.updateCustomerCartUI === 'function') {
                        window.updateCustomerCartUI(data.cart_count, { incrementBy: 1 });
                    }
                } catch (error) {
                    setAjaxMessage('danger', buildAjaxErrorMessage(error, 'Không thể thêm dịch vụ vào giỏ hàng lúc này.'));
                } finally {
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = originalLabel;
                }
            };

            const getEstimatedAmount = () => {
                if (serviceType === 'dich-vu') {
                    const quantity = Number(form.querySelector('[data-role="quantity"]')?.value || 1);
                    return Math.max(1, quantity) * unitPrice;
                }

                if (serviceType === 'phong') {
                    const checkin = form.querySelector('[data-role="checkin"]')?.value;
                    const checkout = form.querySelector('[data-role="checkout"]')?.value;
                    if (!checkin || !checkout) {
                        return unitPrice;
                    }

                    const diff = Math.round((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
                    return Math.max(1, diff) * unitPrice;
                }

                const schedule = form.querySelector('[data-role="schedule"]');
                const selected = schedule?.options[schedule.selectedIndex];
                if (!selected || !selected.value) {
                    return 0;
                }

                const adults = Number(form.querySelector('[data-role="adult-count"]')?.value || 0);
                const children = Number(form.querySelector('[data-role="child-count"]')?.value || 0);
                const adultPrice = Number(selected.dataset.giaNguoiLon || 0);
                const childPrice = Number(selected.dataset.giaTreEm || 0);
                return (adults * adultPrice) + (children * childPrice);
            };

            const updateSummary = () => {
                const total = getEstimatedAmount();
                estimatedTotalEl.textContent = formatCurrency(total);

                if (serviceType === 'dich-vu') {
                    const quantity = Number(form.querySelector('[data-role="quantity"]')?.value || 1);
                    summaryMetaEl.textContent = `${quantity} số lượng × ${formatCurrency(unitPrice)}`;
                    return;
                }

                if (serviceType === 'phong') {
                    const checkin = form.querySelector('[data-role="checkin"]')?.value;
                    const checkout = form.querySelector('[data-role="checkout"]')?.value;
                    if (checkin && checkout) {
                        const diff = Math.max(1, Math.round((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24)));
                        summaryMetaEl.textContent = `${diff} đêm × ${formatCurrency(unitPrice)}`;
                    } else {
                        summaryMetaEl.textContent = 'Chọn ngày nhận/trả để tính tổng';
                    }
                    return;
                }

                const adults = Number(form.querySelector('[data-role="adult-count"]')?.value || 0);
                const children = Number(form.querySelector('[data-role="child-count"]')?.value || 0);
                summaryMetaEl.textContent = `${adults} người lớn, ${children} trẻ em`;
            };

            const buildTransferNote = () => {
                const invoiceId = Number(paymentInfo.next_invoice_id || 0) || 0;
                return `DATDICHVU-HD-${invoiceId}`;
            };

            const buildQrUrl = (amount, transferNote) => {
                const bankBin = paymentInfo.bank_bin || '';
                const accountNo = paymentInfo.account_no || '';
                const accountName = paymentInfo.account_name || '';
                const qrTemplate = paymentInfo.qr_template || 'compact2';

                if (!bankBin || !accountNo) {
                    return '';
                }

                return `https://img.vietqr.io/image/${encodeURIComponent(bankBin)}-${encodeURIComponent(accountNo)}-${encodeURIComponent(qrTemplate)}.png?amount=${encodeURIComponent(String(Math.max(0, Math.round(amount))))}&addInfo=${encodeURIComponent(transferNote).replace(/-/g, '%2D')}&accountName=${encodeURIComponent(accountName)}`;
            };

            const openPaymentModal = () => {
                if (!paymentModal) {
                    return;
                }

                const amount = getEstimatedAmount();
                const transferNote = buildTransferNote();
                const qrUrl = buildQrUrl(amount, transferNote);

                bankNameEl.textContent = paymentInfo.bank_name || 'Chưa cấu hình';
                accountNoEl.textContent = paymentInfo.account_no || 'Chưa cấu hình';
                accountNameEl.textContent = paymentInfo.account_name || 'Chưa cấu hình';
                transferNoteEl.textContent = transferNote;
                paymentAmountEl.textContent = formatCurrency(amount);
                paymentVerifiedInput.value = '0';
                paymentTransferNoteInput.value = '';
                clearStatus(paymentStatusEl);

                if (qrImageEl) {
                    if (qrUrl) {
                        qrImageEl.src = qrUrl;
                        qrImageEl.style.display = 'inline-block';
                    } else {
                        qrImageEl.removeAttribute('src');
                        qrImageEl.style.display = 'none';
                    }
                }

                paymentModal.show();
            };

            const checkRoomAvailability = async () => {
                if (serviceType !== 'phong' || !roomStatusEl) {
                    return;
                }

                const checkin = form.querySelector('[data-role="checkin"]')?.value;
                const checkout = form.querySelector('[data-role="checkout"]')?.value;

                if (!checkin || !checkout) {
                    setStatus(roomStatusEl, 'info', 'Chọn đầy đủ ngày nhận và ngày trả để kiểm tra phòng trống.');
                    return;
                }

                if (new Date(checkout) <= new Date(checkin)) {
                    setStatus(roomStatusEl, 'warning', 'Ngày trả phòng phải sau ngày nhận phòng.');
                    return;
                }

                try {
                    const params = new URLSearchParams({
                        ma_loai: serviceCode,
                        ngay_nhan: checkin,
                        ngay_tra: checkout,
                    });
                    const response = await fetch(`${roomCheckUrl}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    const data = await response.json();
                    if (data.available) {
                        setStatus(roomStatusEl, 'success', `Còn ${data.room_count} phòng trống cho loại phòng này.`);
                    } else {
                        setStatus(roomStatusEl, 'danger', 'Hiện không còn phòng trống trong khoảng ngày đã chọn.');
                    }
                } catch (error) {
                    setStatus(roomStatusEl, 'danger', 'Không thể kiểm tra phòng trống lúc này.');
                    console.error(error);
                }
            };

            const fetchSchedules = async () => {
                if (serviceType !== 'tour') {
                    return;
                }

                const scheduleSelect = form.querySelector('[data-role="schedule"]');
                if (!scheduleSelect) {
                    return;
                }

                try {
                    const response = await fetch(`${tourScheduleUrl}?ma_tour=${encodeURIComponent(serviceCode)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    const data = await response.json();
                    const schedules = Array.isArray(data.schedules) ? data.schedules : [];
                    scheduleSelect.innerHTML = '<option value="">Chọn lịch khởi hành</option>';

                    if (!schedules.length) {
                        setStatus(tourStatusEl, 'warning', 'Tour này hiện chưa có lịch khởi hành khả dụng.');
                        return;
                    }

                    schedules.forEach((schedule) => {
                        const option = document.createElement('option');
                        option.value = schedule.ma_lkh;
                        option.textContent = `${schedule.ngay_khoi_hanh} → ${schedule.ngay_ket_thuc} (${schedule.so_cho_con_lai} chỗ)`;
                        option.dataset.soChoConLai = schedule.so_cho_con_lai;
                        option.dataset.giaNguoiLon = schedule.gia_nguoi_lon;
                        option.dataset.giaTreEm = schedule.gia_tre_em;
                        if (oldSchedule && String(oldSchedule) === String(schedule.ma_lkh)) {
                            option.selected = true;
                        }
                        scheduleSelect.appendChild(option);
                    });

                    clearStatus(tourStatusEl);
                    updateTourStatus();
                } catch (error) {
                    setStatus(tourStatusEl, 'danger', 'Không thể tải lịch khởi hành lúc này.');
                    console.error(error);
                }
            };

            const updateTourStatus = () => {
                if (serviceType !== 'tour' || !tourStatusEl) {
                    return;
                }

                const scheduleSelect = form.querySelector('[data-role="schedule"]');
                const selected = scheduleSelect?.options[scheduleSelect.selectedIndex];
                if (!selected || !selected.value) {
                    setStatus(tourStatusEl, 'info', 'Chọn lịch khởi hành để xem số chỗ còn lại.');
                    return;
                }

                const seats = Number(selected.dataset.soChoConLai || 0);
                const adults = Number(form.querySelector('[data-role="adult-count"]')?.value || 0);
                const children = Number(form.querySelector('[data-role="child-count"]')?.value || 0);
                const totalPeople = adults + children;

                if (totalPeople > seats) {
                    setStatus(tourStatusEl, 'warning', `Vượt quá số chỗ còn lại (${seats} chỗ).`);
                } else {
                    setStatus(tourStatusEl, 'success', `Còn ${seats} chỗ. Lựa chọn hiện tại phù hợp.`);
                }
            };

            confirmPaymentBtn?.addEventListener('click', async () => {
                const transferNote = transferNoteEl?.textContent?.trim() || '';
                if (!transferNote) {
                    setStatus(paymentStatusEl, 'warning', 'Không tìm thấy nội dung chuyển khoản để xác nhận.');
                    return;
                }

                confirmPaymentBtn.disabled = true;
                setStatus(paymentStatusEl, 'info', 'Đang kiểm tra trạng thái thanh toán...');

                try {
                    const response = await fetch(verifyPaymentUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ transfer_note: transferNote }),
                    });

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    const data = await response.json();
                    if (data.paid) {
                        setStatus(paymentStatusEl, 'success', data.message || 'Thanh toán thành công. Đang gửi yêu cầu...');
                        paymentVerifiedInput.value = '1';
                        paymentTransferNoteInput.value = transferNote;
                        allowDirectSubmit = true;
                        setTimeout(() => form.submit(), 1200);
                        return;
                    }

                    setStatus(paymentStatusEl, 'danger', data.message || 'Chưa phát hiện giao dịch phù hợp.');
                } catch (error) {
                    setStatus(paymentStatusEl, 'danger', 'Không thể kiểm tra thanh toán lúc này.');
                    console.error(error);
                } finally {
                    confirmPaymentBtn.disabled = false;
                }
            });

            addToCartBtn?.addEventListener('click', (event) => {
                event.preventDefault();
                submitAddToCartAjax();
            });

            payCounterBtn?.addEventListener('click', () => {
                bookingActionInput.value = 'book_now';
                paymentMethodInput.value = 'counter';
                paymentVerifiedInput.value = '0';
                paymentTransferNoteInput.value = '';
            });

            payOnlineBtn?.addEventListener('click', () => {
                bookingActionInput.value = 'book_now';
                paymentMethodInput.value = 'online';
            });

            form.addEventListener('submit', (event) => {
                if (allowDirectSubmit) {
                    return;
                }

                if (bookingActionInput.value === 'add_to_cart' || paymentMethodInput.value === 'counter') {
                    return;
                }

                event.preventDefault();
                openPaymentModal();
            });

            form.querySelector('[data-role="quantity"]')?.addEventListener('input', updateSummary);
            form.querySelector('[data-role="checkin"]')?.addEventListener('change', () => {
                updateSummary();
                checkRoomAvailability();
            });
            form.querySelector('[data-role="checkout"]')?.addEventListener('change', () => {
                updateSummary();
                checkRoomAvailability();
            });
            form.querySelector('[data-role="schedule"]')?.addEventListener('change', () => {
                updateSummary();
                updateTourStatus();
            });
            form.querySelector('[data-role="adult-count"]')?.addEventListener('input', () => {
                updateSummary();
                updateTourStatus();
            });
            form.querySelector('[data-role="child-count"]')?.addEventListener('input', () => {
                updateSummary();
                updateTourStatus();
            });

            updateSummary();
            if (serviceType === 'phong') {
                checkRoomAvailability();
            }
            if (serviceType === 'tour') {
                fetchSchedules();
            }
        })();
    </script>
@endpush
