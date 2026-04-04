@extends('customer.layout.main')

@section('title', 'Giỏ hàng - Khách hàng')

@section('content')
    <main class="container py-5">
        <section id="cart-section">
            <div class="booking-wrap">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
                    <div>
                        <h2>Giỏ hàng của bạn</h2>
                        <p class="mb-0">Kiểm tra lại các dịch vụ đã chọn trước khi thanh toán online hoặc lưu hóa đơn để thanh toán tại quầy.</p>
                    </div>
                    <div class="text-lg-end">
                        <div class="detail-price">{{ number_format($cartSummary['total'] ?? 0, 0, ',', '.') }} VND</div>
                        <div class="text-muted small">{{ $cartSummary['count'] ?? 0 }} mục trong giỏ hàng</div>
                    </div>
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

                @if (!session()->has('customer_user_id'))
                    <div class="alert alert-info">
                        Bạn không cần đăng nhập để thanh toán giỏ hàng. Chỉ cần nhập đầy đủ <strong>họ tên</strong>, <strong>số điện thoại</strong> và <strong>email</strong> bên dưới.
                    </div>
                @endif

                @if (!empty($cartItems))
                    <div class="cart-item-list">
                        @foreach ($cartItems as $item)
                            <div class="cart-item-row">
                                <div>
                                    <div class="fw-semibold">{{ $item['service_name'] ?? 'Dịch vụ' }}</div>
                                    <div class="small text-muted">{{ ucfirst($item['type'] ?? 'dich-vu') }}</div>
                                    <div class="small text-muted">{{ $item['schedule_label'] ?? '' }}</div>
                                    <div class="small text-muted">{{ $item['quantity_label'] ?? '' }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                    <strong>{{ number_format($item['estimated_total'] ?? 0, 0, ',', '.') }} VND</strong>
                                    <form method="POST" action="{{ route('customer.cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item['id'] ?? '' }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <form id="cartCheckoutForm" method="POST" action="{{ route('customer.cart.checkout') }}" class="row g-3 align-items-end">
                            @csrf
                            <input type="hidden" id="cartPaymentMethodInput" name="payment_method" value="online">
                            <input type="hidden" id="cartPaymentVerified" name="payment_verified" value="0">
                            <input type="hidden" id="cartPaymentTransferNoteInput" name="payment_transfer_note" value="">

                            <div class="col-12">
                                <h3 class="h5 mb-1">Thông tin khách hàng</h3>
                                <p class="text-muted mb-0">Cần nhập đủ 3 thông tin bên dưới trước khi xác nhận thanh toán hoặc lưu hóa đơn.</p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Họ tên</label>
                                <input
                                    type="text"
                                    name="ho_ten"
                                    class="form-control"
                                    value="{{ old('ho_ten', $customerProfile->TenKH ?? session('customer_guest_profile.ho_ten', '')) }}"
                                    required
                                >
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số điện thoại</label>
                                <input
                                    type="text"
                                    name="so_dien_thoai"
                                    class="form-control"
                                    value="{{ old('so_dien_thoai', $customerProfile->SDT ?? session('customer_guest_profile.so_dien_thoai', '')) }}"
                                    required
                                >
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $customerProfile->Email ?? session('customer_guest_profile.email', '')) }}"
                                    required
                                >
                            </div>

                            <div class="col-12 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mt-2">
                                <a href="{{ route('customer.booking') }}" class="btn btn-outline-success">Tiếp tục chọn dịch vụ</a>

                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <button type="submit" class="btn btn-outline-secondary" id="cartPayAtCounterBtn">Lưu hóa đơn / thanh toán tại quầy</button>
                                    <button type="submit" class="btn btn-book" id="cartPayOnlineBtn">Thanh toán online</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="empty-box">
                        Giỏ hàng đang trống. Hãy quay lại trang dịch vụ để thêm lựa chọn mới.
                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a href="{{ route('customer.booking') }}" class="btn btn-book">Đến trang đặt dịch vụ</a>
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-success">Về trang chủ</a>
                    </div>
                @endif

                <div class="modal fade" id="cartPaymentQrModal" tabindex="-1" aria-labelledby="cartPaymentQrModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cartPaymentQrModalLabel">Thanh toán giỏ hàng qua mã QR</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-4 align-items-start">
                                    <div class="col-md-5 text-center">
                                        <img id="cartPaymentQrImage" src="" alt="QR thanh toán" class="img-fluid rounded border" style="max-height: 320px;">
                                        <div class="small text-muted mt-2">Quét mã QR bằng app ngân hàng để chuyển khoản</div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="mb-2"><strong>Ngân hàng:</strong> <span id="cartPaymentBankName"></span></div>
                                        <div class="mb-2"><strong>Số tài khoản:</strong> <span id="cartPaymentAccountNo"></span></div>
                                        <div class="mb-2"><strong>Chủ tài khoản:</strong> <span id="cartPaymentAccountName"></span></div>
                                        <div class="mb-2"><strong>Nội dung CK:</strong> <span id="cartPaymentTransferNote"></span></div>
                                        <div class="mb-2"><strong>Số tiền tạm tính:</strong> <span id="cartPaymentAmountText"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer flex-column align-items-stretch gap-2">
                                <div id="cartPaymentStatusMsg" class="alert mb-0 d-none" role="alert"></div>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="cartPaymentBackBtn">Quay lại</button>
                                    <button type="button" id="cartConfirmPaymentSubmit" class="btn btn-book">
                                        <span id="cartConfirmPaymentSpinner" class="spinner-border spinner-border-sm me-1 d-none" role="status"></span>
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
        (() => {
            const cartCheckoutFormEl = document.getElementById('cartCheckoutForm');
            if (!cartCheckoutFormEl) {
                return;
            }

            const paymentInfo = @json($paymentInfo);
            const cartTotal = Number(@json((float) ($cartSummary['total'] ?? 0))) || 0;
            const cartPaymentMethodInputEl = document.getElementById('cartPaymentMethodInput');
            const cartPaymentVerifiedEl = document.getElementById('cartPaymentVerified');
            const cartPaymentTransferNoteInputEl = document.getElementById('cartPaymentTransferNoteInput');
            const cartPayOnlineBtnEl = document.getElementById('cartPayOnlineBtn');
            const cartPayAtCounterBtnEl = document.getElementById('cartPayAtCounterBtn');
            const cartPaymentModalEl = document.getElementById('cartPaymentQrModal');
            const cartPaymentQrImageEl = document.getElementById('cartPaymentQrImage');
            const cartPaymentBankNameEl = document.getElementById('cartPaymentBankName');
            const cartPaymentAccountNoEl = document.getElementById('cartPaymentAccountNo');
            const cartPaymentAccountNameEl = document.getElementById('cartPaymentAccountName');
            const cartPaymentTransferNoteEl = document.getElementById('cartPaymentTransferNote');
            const cartPaymentAmountTextEl = document.getElementById('cartPaymentAmountText');
            const cartPaymentStatusMsgEl = document.getElementById('cartPaymentStatusMsg');
            const cartConfirmPaymentSubmitBtn = document.getElementById('cartConfirmPaymentSubmit');
            const cartConfirmPaymentSpinnerEl = document.getElementById('cartConfirmPaymentSpinner');
            const cartPaymentBackBtnEl = document.getElementById('cartPaymentBackBtn');
            const checkPaymentUrl = @json(route('payment.sepay.webhook'));
            const cartPaymentModal = window.bootstrap ? new bootstrap.Modal(cartPaymentModalEl) : null;
            let allowDirectSubmit = false;

            function formatCurrency(value) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                }).format(value || 0);
            }

            function buildTransferNote() {
                const invoiceId = Number(paymentInfo.next_invoice_id || 0) || 0;
                return `DATDICHVU-HD-${invoiceId}`;
            }

            function buildQrUrl(amount, transferNote) {
                const bankBin = paymentInfo.bank_bin || '';
                const accountNo = paymentInfo.account_no || '';
                const accountName = paymentInfo.account_name || '';
                const qrTemplate = paymentInfo.qr_template || 'compact2';

                if (!bankBin || !accountNo) {
                    return '';
                }

                const strictEncode = (value) => encodeURIComponent(String(value || '')).replace(/-/g, '%2D');
                const params = [
                    `amount=${encodeURIComponent(String(Math.max(0, Math.round(amount))))}`,
                    `addInfo=${strictEncode(transferNote)}`,
                    `accountName=${encodeURIComponent(accountName)}`,
                ].join('&');

                return `https://img.vietqr.io/image/${encodeURIComponent(bankBin)}-${encodeURIComponent(accountNo)}-${encodeURIComponent(qrTemplate)}.png?${params}`;
            }

            function setPaymentStatus(type, message) {
                cartPaymentStatusMsgEl.className = 'alert mb-0 alert-' + type;
                cartPaymentStatusMsgEl.textContent = message;
            }

            function openPaymentModalBeforeSubmit() {
                if (!cartPaymentModal) {
                    setPaymentStatus('danger', 'Không thể mở hộp thoại thanh toán.');
                    return;
                }

                const transferNote = buildTransferNote();
                const qrUrl = buildQrUrl(cartTotal, transferNote);

                cartPaymentBankNameEl.textContent = paymentInfo.bank_name || 'Chưa cấu hình';
                cartPaymentAccountNoEl.textContent = paymentInfo.account_no || 'Chưa cấu hình';
                cartPaymentAccountNameEl.textContent = paymentInfo.account_name || 'Chưa cấu hình';
                cartPaymentTransferNoteEl.textContent = transferNote;
                cartPaymentAmountTextEl.textContent = formatCurrency(cartTotal);
                cartPaymentVerifiedEl.value = '0';
                cartPaymentTransferNoteInputEl.value = '';
                cartPaymentStatusMsgEl.className = 'alert mb-0 d-none';
                cartPaymentStatusMsgEl.textContent = '';
                cartConfirmPaymentSubmitBtn.disabled = false;
                cartPaymentBackBtnEl.disabled = false;

                if (qrUrl) {
                    cartPaymentQrImageEl.src = qrUrl;
                    cartPaymentQrImageEl.style.display = 'inline-block';
                } else {
                    cartPaymentQrImageEl.removeAttribute('src');
                    cartPaymentQrImageEl.style.display = 'none';
                }

                cartPaymentModal.show();
            }

            async function verifyPayment() {
                const transferNote = cartPaymentTransferNoteEl.textContent.trim();
                if (!transferNote) {
                    setPaymentStatus('warning', 'Không tìm thấy nội dung chuyển khoản để kiểm tra.');
                    return;
                }

                cartConfirmPaymentSubmitBtn.disabled = true;
                cartConfirmPaymentSpinnerEl.classList.remove('d-none');
                cartPaymentBackBtnEl.disabled = true;
                setPaymentStatus('info', 'Đang kiểm tra biến động số dư tài khoản...');

                try {
                    const resp = await fetch(checkPaymentUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ transfer_note: transferNote }),
                    });

                    if (!resp.ok) {
                        throw new Error('HTTP ' + resp.status);
                    }

                    const data = await resp.json();

                    if (data.paid) {
                        setPaymentStatus('success', data.message || 'Xác nhận thanh toán thành công! Đang gửi yêu cầu...');
                        cartConfirmPaymentSpinnerEl.classList.add('d-none');
                        cartPaymentVerifiedEl.value = '1';
                        cartPaymentTransferNoteInputEl.value = transferNote;
                        setTimeout(() => {
                            allowDirectSubmit = true;
                            cartCheckoutFormEl.submit();
                        }, 1500);
                    } else {
                        setPaymentStatus('danger', data.message || 'Chưa tìm thấy biến động số dư. Vui lòng thử lại sau vài giây.');
                        cartConfirmPaymentSubmitBtn.disabled = false;
                        cartConfirmPaymentSpinnerEl.classList.add('d-none');
                        cartPaymentBackBtnEl.disabled = false;
                    }
                } catch (err) {
                    setPaymentStatus('danger', 'Lỗi kết nối khi kiểm tra thanh toán. Vui lòng thử lại.');
                    cartConfirmPaymentSubmitBtn.disabled = false;
                    cartConfirmPaymentSpinnerEl.classList.add('d-none');
                    cartPaymentBackBtnEl.disabled = false;
                    console.error('checkPaymentStatus error:', err);
                }
            }

            cartPayAtCounterBtnEl.addEventListener('click', () => {
                cartPaymentMethodInputEl.value = 'counter';
                cartPaymentVerifiedEl.value = '0';
                cartPaymentTransferNoteInputEl.value = '';
            });

            cartPayOnlineBtnEl.addEventListener('click', () => {
                cartPaymentMethodInputEl.value = 'online';
            });

            cartCheckoutFormEl.addEventListener('submit', (event) => {
                if (allowDirectSubmit) {
                    return;
                }

                if (!cartCheckoutFormEl.reportValidity()) {
                    return;
                }

                if (cartPaymentMethodInputEl.value === 'counter') {
                    return;
                }

                event.preventDefault();
                openPaymentModalBeforeSubmit();
            });

            cartConfirmPaymentSubmitBtn.addEventListener('click', verifyPayment);
        })();
    </script>
@endpush
