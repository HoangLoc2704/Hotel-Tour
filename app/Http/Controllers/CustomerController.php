<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Models\HDDichVu;
use App\Models\HDTOUR;
use App\Models\HDPhong;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\LichKhoiHanh;
use App\Models\LoaiPhong;
use App\Models\Phong;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $dichVus = DichVu::query()
            ->select(['MaDV', 'TenDV', 'GiaDV'])
            ->where('TrangThai', 1)
            ->orderBy('TenDV')
            ->limit(6)
            ->get();

        $phongs = LoaiPhong::query()
            ->select(['MaLoai', 'TenLoai', 'GiaPhong', 'SoLuongNguoi', 'HinhAnh', 'MoTa'])
            ->orderBy('TenLoai')
            ->limit(6)
            ->get();

        $tours = Tour::query()
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon', 'DiaDiemKhoiHanh', 'ThoiLuong', 'HinhAnh', 'MoTa'])
            ->where('TrangThai', 1)
            ->orderBy('TenTour')
            ->limit(6)
            ->get();

        return view('customer.index', compact('dichVus', 'phongs', 'tours'));
    }

    public function hotelServices(Request $request): View
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:100',
            'ma_loai' => 'nullable|string|max:30',
            'so_nguoi' => 'nullable|integer|min:1|max:20',
            'gia_tu' => 'nullable|numeric|min:0',
            'gia_den' => 'nullable|numeric|min:0',
        ]);

        $items = LoaiPhong::query()
            ->select(['MaLoai', 'TenLoai', 'GiaPhong', 'SoLuongNguoi', 'HinhAnh', 'MoTa'])
            ->when(!empty($filters['q']), function ($query) use ($filters) {
                $query->where(function ($subQuery) use ($filters) {
                    $subQuery->where('TenLoai', 'like', '%' . $filters['q'] . '%')
                        ->orWhere('MaLoai', 'like', '%' . $filters['q'] . '%');
                });
            })
            ->when(!empty($filters['ma_loai']), function ($query) use ($filters) {
                $query->where('MaLoai', $filters['ma_loai']);
            })
            ->when(!empty($filters['so_nguoi']), function ($query) use ($filters) {
                $query->where('SoLuongNguoi', '>=', (int) $filters['so_nguoi']);
            })
            ->when(isset($filters['gia_tu']) && $filters['gia_tu'] !== null, function ($query) use ($filters) {
                $query->where('GiaPhong', '>=', $filters['gia_tu']);
            })
            ->when(isset($filters['gia_den']) && $filters['gia_den'] !== null, function ($query) use ($filters) {
                $query->where('GiaPhong', '<=', $filters['gia_den']);
            })
            ->orderBy('GiaPhong')
            ->orderBy('TenLoai')
            ->paginate(12)
            ->withQueryString();

        $roomTypes = LoaiPhong::query()
            ->select(['MaLoai', 'TenLoai'])
            ->orderBy('TenLoai')
            ->get();

        return view('customer.service-catalog', [
            'category' => 'hotel',
            'title' => 'Khách sạn',
            'subtitle' => 'Lọc nhanh theo loại phòng, sức chứa và mức giá phù hợp nhu cầu.',
            'items' => $items,
            'filters' => $filters,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function tourServices(Request $request): View
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:100',
            'dia_diem' => 'nullable|string|max:100',
            'thoi_luong_tu' => 'nullable|integer|min:1|max:30',
            'thoi_luong_den' => 'nullable|integer|min:1|max:30',
            'gia_tu' => 'nullable|numeric|min:0',
            'gia_den' => 'nullable|numeric|min:0',
        ]);

        $query = Tour::query()
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon', 'GiaTourTreEm', 'ThoiLuong', 'DiaDiemKhoiHanh', 'HinhAnh', 'MoTa'])
            ->where('TrangThai', 1);

        if (!empty($filters['q'])) {
            $query->where('TenTour', 'like', '%' . $filters['q'] . '%');
        }
        if (!empty($filters['dia_diem'])) {
            $query->where('DiaDiemKhoiHanh', $filters['dia_diem']);
        }
        if (!empty($filters['thoi_luong_tu'])) {
            $query->where('ThoiLuong', '>=', (int) $filters['thoi_luong_tu']);
        }
        if (!empty($filters['thoi_luong_den'])) {
            $query->where('ThoiLuong', '<=', (int) $filters['thoi_luong_den']);
        }
        if (isset($filters['gia_tu']) && $filters['gia_tu'] !== null) {
            $query->where('GiaTourNguoiLon', '>=', $filters['gia_tu']);
        }
        if (isset($filters['gia_den']) && $filters['gia_den'] !== null) {
            $query->where('GiaTourNguoiLon', '<=', $filters['gia_den']);
        }

        $items = $query
            ->orderBy('GiaTourNguoiLon')
            ->orderBy('TenTour')
            ->paginate(12)
            ->withQueryString();

        $destinations = Tour::query()
            ->where('TrangThai', 1)
            ->whereNotNull('DiaDiemKhoiHanh')
            ->where('DiaDiemKhoiHanh', '<>', '')
            ->distinct()
            ->orderBy('DiaDiemKhoiHanh')
            ->pluck('DiaDiemKhoiHanh');

        return view('customer.service-catalog', [
            'category' => 'tour',
            'title' => 'Tour du lịch',
            'subtitle' => 'Khám phá tour theo điểm khởi hành, thời lượng và tầm giá mong muốn.',
            'items' => $items,
            'filters' => $filters,
            'destinations' => $destinations,
        ]);
    }

    public function addonServices(Request $request): View
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:100',
            'gia_tu' => 'nullable|numeric|min:0',
            'gia_den' => 'nullable|numeric|min:0',
        ]);

        $query = DichVu::query()
            ->select(['MaDV', 'TenDV', 'GiaDV', 'TrangThai'])
            ->where('TrangThai', 1);

        if (!empty($filters['q'])) {
            $query->where('TenDV', 'like', '%' . $filters['q'] . '%');
        }
        if (isset($filters['gia_tu']) && $filters['gia_tu'] !== null) {
            $query->where('GiaDV', '>=', $filters['gia_tu']);
        }
        if (isset($filters['gia_den']) && $filters['gia_den'] !== null) {
            $query->where('GiaDV', '<=', $filters['gia_den']);
        }

        $items = $query
            ->orderBy('GiaDV')
            ->orderBy('TenDV')
            ->paginate(12)
            ->withQueryString();

        return view('customer.service-catalog', [
            'category' => 'addon',
            'title' => 'Dịch vụ đi kèm',
            'subtitle' => 'Lọc nhanh các dịch vụ bổ sung theo tên và ngân sách mong muốn.',
            'items' => $items,
            'filters' => $filters,
        ]);
    }

    public function booking(): View
    {
        $dichVus = DichVu::query()
            ->select(['MaDV', 'TenDV', 'GiaDV'])
            ->where('TrangThai', 1)
            ->orderBy('TenDV')
            ->get();

        $phongs = LoaiPhong::query()
            ->select(['MaLoai', 'TenLoai', 'GiaPhong'])
            ->orderBy('TenLoai')
            ->get();

        $tours = Tour::query()
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon', 'GiaTourTreEm'])
            ->where('TrangThai', 1)
            ->orderBy('TenTour')
            ->get();

        $dichVuOptions = $dichVus->map(function ($item) {
            return [
                'value' => $item->MaDV,
                'label' => $item->TenDV,
                'price' => (float) $item->GiaDV,
            ];
        })->values();

        $phongOptions = $phongs->map(function ($item) {
            return [
                'value' => (string) $item->MaLoai,
                'label' => $item->TenLoai,
                'price' => (float) $item->GiaPhong,
            ];
        })->values();

        $tourOptions = $tours->map(function ($item) {
            return [
                'value' => $item->MaTour,
                'label' => $item->TenTour,
                'adult_price' => (float) $item->GiaTourNguoiLon,
                'child_price' => (float) $item->GiaTourTreEm,
            ];
        })->values();

        $customerProfile = $this->getCustomerProfile();
        $paymentInfo = $this->getPaymentInfo();

        return view('customer.booking', compact(
            'dichVus',
            'phongs',
            'tours',
            'customerProfile',
            'dichVuOptions',
            'phongOptions',
            'tourOptions',
            'paymentInfo'
        ));
    }

    public function cart(): View
    {
        $customerProfile = $this->getCustomerProfile();
        $paymentInfo = $this->getPaymentInfo();
        $cartItems = $this->getCartItems();
        $cartSummary = $this->buildCartSummary($cartItems);

        return view('customer.cart', compact(
            'customerProfile',
            'paymentInfo',
            'cartItems',
            'cartSummary'
        ));
    }

    public function invoices(Request $request): View|RedirectResponse
    {
        $customerId = Session::get('customer_user_id');
        if (!$customerId) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Vui lòng đăng nhập để xem hóa đơn của bạn.');
        }

        $filters = $request->validate([
            'thanh_toan' => 'nullable|in:0,1',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $hoaDonQuery = HoaDon::query()
            ->select(['MaHD', 'MaKH', 'NgayTao', 'ThanhTien', 'TrangThai', 'ThanhToan'])
            ->where('MaKH', $customerId);

        if (array_key_exists('thanh_toan', $filters) && $filters['thanh_toan'] !== null) {
            $hoaDonQuery->where('ThanhToan', (int) $filters['thanh_toan']);
        }

        if (!empty($filters['from_date'])) {
            $hoaDonQuery->whereDate('NgayTao', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $hoaDonQuery->whereDate('NgayTao', '<=', $filters['to_date']);
        }

        $hoaDons = $hoaDonQuery
            ->orderByDesc('NgayTao')
            ->orderByDesc('MaHD')
            ->paginate(10)
            ->withQueryString();

        $customerProfile = $this->getCustomerProfile();

        return view('customer.invoices', compact('hoaDons', 'filters', 'customerProfile'));
    }

    public function showInvoice(string $maHD): View|RedirectResponse
    {
        $customerId = Session::get('customer_user_id');
        if (!$customerId) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Vui lòng đăng nhập để xem hóa đơn của bạn.');
        }

        $hoaDon = HoaDon::query()
            ->with([
                'khachHang:MaKH,TenKH,SDT,Email',
                'hdPhongs.phong:MaPhong,TenPhong,MaLoai',
                'hdPhongs.phong.loaiPhong:MaLoai,GiaPhong',
                'hdDichVus.dichVu:MaDV,TenDV,GiaDV',
                'hdTours.lichKhoiHanh:MaLKH,MaTour,NgayKhoiHanh,NgayKetThuc',
                'hdTours.lichKhoiHanh.tour:MaTour,TenTour,GiaTourNguoiLon,GiaTourTreEm',
            ])
            ->where('MaKH', $customerId)
            ->findOrFail($maHD);

        $customerProfile = $this->getCustomerProfile();

        return view('customer.invoice-detail', compact('hoaDon', 'customerProfile'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customerId = Session::get('customer_user_id');
        if (!$customerId) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Vui lòng đăng nhập để cập nhật thông tin.');
        }

        $validated = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'gioi_tinh' => 'required|in:0,1',
            'mat_khau' => 'nullable|string|min:6|confirmed',
        ]);

        $khachHang = KhachHang::query()->find($customerId);
        if (!$khachHang) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Không tìm thấy tài khoản khách hàng. Vui lòng đăng nhập lại.');
        }

        $updates = [
            'TenKH' => trim((string) $validated['ho_ten']),
            'GioiTinh' => (int) $validated['gioi_tinh'],
        ];

        if (!empty($validated['mat_khau'])) {
            $updates['MatKhau'] = Hash::make($validated['mat_khau']);
        }

        $khachHang->update($updates);

        Session::put('customer_user_name', $khachHang->TenKH);
        Session::put('customer_user_phone', $khachHang->SDT);
        Session::put('customer_user_email', $khachHang->Email);

        return back()->with('success', 'Cập nhật thông tin khách hàng thành công.');
    }

    public function cancelInvoice(string $maHD): RedirectResponse
    {
        $customerId = Session::get('customer_user_id');
        if (!$customerId) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Vui lòng đăng nhập để hủy hóa đơn của bạn.');
        }

        try {
            $result = DB::transaction(function () use ($customerId, $maHD) {
                $hoaDon = HoaDon::query()
                    ->where('MaKH', $customerId)
                    ->where('MaHD', $maHD)
                    ->lockForUpdate()
                    ->first();

                if (!$hoaDon) {
                    return 'not_found';
                }

                if ((int) $hoaDon->TrangThai === 0) {
                    return 'already_cancelled';
                }

                $hdTours = HDTOUR::query()
                    ->where('MaHD', $hoaDon->MaHD)
                    ->lockForUpdate()
                    ->get(['MaLKH', 'SoNguoiLon', 'SoTreEm', 'TrangThai']);

                foreach ($hdTours as $hdTour) {
                    if ((int) $hdTour->TrangThai !== 1) {
                        continue;
                    }

                    $tongNguoi = (int) $hdTour->SoNguoiLon + (int) $hdTour->SoTreEm;
                    if ($tongNguoi <= 0) {
                        continue;
                    }

                    LichKhoiHanh::query()
                        ->where('MaLKH', $hdTour->MaLKH)
                        ->lockForUpdate()
                        ->increment('SoChoConLai', $tongNguoi);
                }

                HDPhong::query()
                    ->where('MaHD', $hoaDon->MaHD)
                    ->where('TrangThai', 1)
                    ->update(['TrangThai' => 0]);

                HDDichVu::query()
                    ->where('MaHD', $hoaDon->MaHD)
                    ->where('TrangThai', 1)
                    ->update(['TrangThai' => 0]);

                HDTOUR::query()
                    ->where('MaHD', $hoaDon->MaHD)
                    ->where('TrangThai', 1)
                    ->update(['TrangThai' => 0]);

                $hoaDon->TrangThai = 0;
                $hoaDon->save();

                return 'cancelled';
            });
        } catch (\Throwable $exception) {
            Log::error('Customer invoice cancellation failed.', [
                'ma_hd' => $maHD,
                'customer_id' => $customerId,
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('customer.invoices')
                ->with('error', 'Không thể hủy hóa đơn lúc này. Vui lòng thử lại.');
        }

        if ($result === 'not_found') {
            return redirect()
                ->route('customer.invoices')
                ->with('error', 'Không tìm thấy hóa đơn cần hủy.');
        }

        if ($result === 'already_cancelled') {
            return redirect()
                ->route('customer.invoices')
                ->with('info', 'Hóa đơn này đã ở trạng thái vô hiệu trước đó.');
        }

        return redirect()
            ->route('customer.invoices')
            ->with('success', 'Đã hủy hóa đơn thành công.');
    }

    public function login(): View
    {
        return view('customer.login');
    }

    public function register(): View
    {
        return view('customer.register');
    }

    public function forgotPassword(): View
    {
        return view('customer.forgetpassword');
    }

    public function forgotPasswordOtp(): View|RedirectResponse
    {
        $resetPayload = Session::get('customer_password_reset_payload');

        if (!is_array($resetPayload) || empty($resetPayload['email'])) {
            return redirect()
                ->route('customer.password.request')
                ->with('error', 'Vui lòng nhập email tài khoản trước khi xác thực OTP.');
        }

        $otpExpiresAt = Session::get('customer_password_reset_otp_expires_at');

        return view('customer.forgot-password-otp', compact('resetPayload', 'otpExpiresAt'));
    }

    public function sendForgotPasswordOtp(Request $request): RedirectResponse
    {
        $isResend = $request->boolean('resend');

        if ($isResend) {
            $payload = Session::get('customer_password_reset_payload');

            if (!is_array($payload) || empty($payload['email']) || empty($payload['customer_id'])) {
                return redirect()
                    ->route('customer.password.request')
                    ->with('error', 'Phiên quên mật khẩu đã hết hạn. Vui lòng nhập lại email.');
            }
        } else {
            $validated = $request->validate([
                'email' => 'required|email|max:100',
            ]);

            $email = Str::lower(trim((string) $validated['email']));
            $khachHang = KhachHang::query()
                ->where('Email', $email)
                ->where('TrangThai', 1)
                ->first();

            if (!$khachHang) {
                throw ValidationException::withMessages([
                    'email' => 'Không tìm thấy tài khoản khách hàng với email này.',
                ]);
            }

            $payload = [
                'customer_id' => $khachHang->MaKH,
                'customer_name' => $khachHang->TenKH,
                'email' => $email,
            ];

            Session::put('customer_password_reset_payload', $payload);
            Session::forget('customer_password_reset_verified');
        }

        $otp = (string) random_int(100000, 999999);
        Session::put('customer_password_reset_otp_hash', Hash::make($otp));
        Session::put('customer_password_reset_otp_expires_at', now()->addMinutes(5)->timestamp);

        try {
            $this->sendForgotPasswordOtpEmail((string) $payload['email'], $otp);
        } catch (\Exception $e) {
            return redirect()
                ->route($isResend ? 'customer.password.otp' : 'customer.password.request')
                ->withErrors([
                    'otp' => 'Không thể gửi mã OTP lúc này. Vui lòng thử lại sau.',
                ]);
        }

        return redirect()
            ->route('customer.password.otp')
            ->with('success', $isResend
                ? 'Đã gửi lại mã OTP về email của bạn.'
                : 'Đã gửi mã OTP xác thực về email của bạn. Vui lòng nhập OTP để tiếp tục.');
    }

    public function verifyForgotPasswordOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otpHash = Session::get('customer_password_reset_otp_hash');
        $otpExpiresAt = (int) Session::get('customer_password_reset_otp_expires_at', 0);
        $payload = Session::get('customer_password_reset_payload');

        if (empty($otpHash) || !is_array($payload) || now()->timestamp > $otpExpiresAt) {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('customer.password.request')
                ->withErrors([
                    'otp' => 'Mã OTP đã hết hạn hoặc không tồn tại. Vui lòng nhập lại email để tiếp tục.',
                ]);
        }

        if (!Hash::check((string) $validated['otp'], (string) $otpHash)) {
            return redirect()
                ->route('customer.password.otp')
                ->withErrors([
                    'otp' => 'Mã OTP không đúng. Vui lòng kiểm tra lại.',
                ])
                ->withInput();
        }

        Session::put('customer_password_reset_verified', true);

        return redirect()
            ->route('customer.password.reset')
            ->with('success', 'Xác thực OTP thành công. Vui lòng nhập mật khẩu mới.');
    }

    public function resetPassword(): View|RedirectResponse
    {
        $resetPayload = Session::get('customer_password_reset_payload');
        $isVerified = Session::get('customer_password_reset_verified', false);

        if (!is_array($resetPayload) || empty($resetPayload['email'])) {
            return redirect()
                ->route('customer.password.request')
                ->with('error', 'Phiên đặt lại mật khẩu không hợp lệ. Vui lòng thử lại.');
        }

        if (!$isVerified) {
            return redirect()
                ->route('customer.password.otp')
                ->with('error', 'Vui lòng xác thực OTP trước khi đổi mật khẩu.');
        }

        return view('customer.reset-password', compact('resetPayload'));
    }

    public function updateForgotPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mat_khau' => 'required|string|min:6|confirmed',
        ]);

        $payload = Session::get('customer_password_reset_payload');
        $isVerified = Session::get('customer_password_reset_verified', false);

        if (!is_array($payload) || empty($payload['customer_id']) || !$isVerified) {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('customer.password.request')
                ->with('error', 'Phiên đặt lại mật khẩu đã hết hạn. Vui lòng thực hiện lại từ đầu.');
        }

        $khachHang = KhachHang::query()->find($payload['customer_id']);

        if (!$khachHang) {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('customer.password.request')
                ->with('error', 'Không tìm thấy tài khoản khách hàng. Vui lòng thử lại.');
        }

        $khachHang->update([
            'MatKhau' => Hash::make($validated['mat_khau']),
        ]);

        $this->clearForgotPasswordSession();

        return redirect()
            ->route('customer.login')
            ->with('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại bằng mật khẩu mới.');
    }

    public function registerOtp(): View|RedirectResponse
    {
        $pendingRegistration = Session::get('customer_register_payload');

        if (!is_array($pendingRegistration) || empty($pendingRegistration['email'])) {
            return redirect()
                ->route('customer.register')
                ->with('error', 'Vui lòng nhập thông tin đăng ký trước khi xác thực OTP.');
        }

        $otpExpiresAt = Session::get('customer_register_otp_expires_at');

        return view('customer.register-otp', compact('pendingRegistration', 'otpExpiresAt'));
    }

    public function sendRegisterOtp(Request $request): RedirectResponse
    {
        $isResend = $request->boolean('resend');

        if ($isResend) {
            $payload = Session::get('customer_register_payload');

            if (!is_array($payload) || empty($payload['email'])) {
                return redirect()
                    ->route('customer.register')
                    ->with('error', 'Phiên đăng ký đã hết hạn. Vui lòng nhập lại thông tin.');
            }
        } else {
            $validated = $request->validate([
                'ho_ten' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'so_dien_thoai' => 'required|string|max:20',
                'mat_khau' => 'required|string|min:6|confirmed',
            ]);

            [$matchedCustomer, $duplicateErrors] = $this->resolveRegisterCustomer(
                (string) $validated['email'],
                (string) $validated['so_dien_thoai']
            );

            if (!empty($duplicateErrors)) {
                throw ValidationException::withMessages($duplicateErrors);
            }

            $payload = [
                'ho_ten' => trim((string) $validated['ho_ten']),
                'email' => Str::lower(trim((string) $validated['email'])),
                'so_dien_thoai' => trim((string) $validated['so_dien_thoai']),
                'mat_khau_hash' => Hash::make($validated['mat_khau']),
                'matched_customer_id' => $matchedCustomer?->MaKH,
            ];

            Session::put('customer_register_payload', $payload);
        }

        $otp = (string) random_int(100000, 999999);
        Session::put('customer_register_otp_hash', Hash::make($otp));
        Session::put('customer_register_otp_expires_at', now()->addMinutes(5)->timestamp);

        try {
            $this->sendRegisterOtpEmail((string) $payload['email'], $otp);
        } catch (\Exception $e) {
            return redirect()
                ->route($isResend ? 'customer.register.otp' : 'customer.register')
                ->withErrors([
                    'otp' => 'Không thể gửi mã OTP lúc này. Vui lòng thử lại sau.',
                ]);
        }

        return redirect()
            ->route('customer.register.otp')
            ->with('success', $isResend
                ? 'Đã gửi lại mã OTP về email của bạn.'
                : 'Đã gửi mã OTP về email của bạn. Vui lòng nhập OTP để hoàn tất đăng ký.');
    }

    public function submitRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otpHash = Session::get('customer_register_otp_hash');
        $otpExpiresAt = (int) Session::get('customer_register_otp_expires_at', 0);
        $payload = Session::get('customer_register_payload');

        if (empty($otpHash) || !is_array($payload) || now()->timestamp > $otpExpiresAt) {
            Session::forget(['customer_register_otp_hash', 'customer_register_otp_expires_at', 'customer_register_payload']);

            return redirect()
                ->route('customer.register')
                ->withErrors([
                    'otp' => 'Mã OTP đã hết hạn hoặc không tồn tại. Vui lòng thực hiện đăng ký lại.',
                ]);
        }

        if (!Hash::check((string) $validated['otp'], (string) $otpHash)) {
            return back()->withErrors([
                'otp' => 'Mã OTP không đúng. Vui lòng kiểm tra lại.',
            ]);
        }

        [$matchedCustomer, $duplicateErrors] = $this->resolveRegisterCustomer(
            (string) ($payload['email'] ?? ''),
            (string) ($payload['so_dien_thoai'] ?? '')
        );

        if (!empty($duplicateErrors)) {
            Session::forget(['customer_register_otp_hash', 'customer_register_otp_expires_at', 'customer_register_payload']);

            return redirect()
                ->route('customer.register')
                ->withErrors($duplicateErrors)
                ->withInput([
                    'ho_ten' => $payload['ho_ten'] ?? '',
                    'email' => $payload['email'] ?? '',
                    'so_dien_thoai' => $payload['so_dien_thoai'] ?? '',
                ]);
        }

        $customerData = [
            'TenKH' => trim((string) ($payload['ho_ten'] ?? '')),
            'Email' => Str::lower(trim((string) ($payload['email'] ?? ''))),
            'SDT' => trim((string) ($payload['so_dien_thoai'] ?? '')),
            'MatKhau' => $payload['mat_khau_hash'] ?? Hash::make(Str::random(12)),
            'TrangThai' => 1,
        ];

        if ($matchedCustomer && (int) $matchedCustomer->TrangThai === 0) {
            $matchedCustomer->update($customerData);
            $successMessage = 'Xác thực OTP thành công. Tài khoản khách hàng đã được cập nhật và kích hoạt.';
        } else {
            KhachHang::query()->create($customerData);
            $successMessage = 'Đăng ký tài khoản khách hàng thành công. Vui lòng đăng nhập để tiếp tục.';
        }

        Session::forget(['customer_register_otp_hash', 'customer_register_otp_expires_at', 'customer_register_payload']);

        return redirect()->route('customer.login')->with('success', $successMessage);
    }

    private function resolveRegisterCustomer(string $email, string $soDienThoai): array
    {
        $normalizedEmail = Str::lower(trim($email));
        $normalizedPhone = trim($soDienThoai);

        $emailCustomer = KhachHang::query()
            ->where('Email', $normalizedEmail)
            ->first();

        $phoneCustomer = KhachHang::query()
            ->where('SDT', $normalizedPhone)
            ->first();

        $errors = [];

        if ($emailCustomer && (int) $emailCustomer->TrangThai === 1) {
            $errors['email'] = 'Email này đã tồn tại trong hệ thống. Vui lòng nhập email khác.';
        }

        if ($phoneCustomer && (int) $phoneCustomer->TrangThai === 1) {
            $errors['so_dien_thoai'] = 'Số điện thoại này đã tồn tại trong hệ thống. Vui lòng nhập số khác.';
        }

        if ($emailCustomer && $phoneCustomer && (string) $emailCustomer->MaKH !== (string) $phoneCustomer->MaKH) {
            $errors['email'] = $errors['email'] ?? 'Email và số điện thoại đang trùng với hai khách hàng khác nhau. Vui lòng kiểm tra lại.';
            $errors['so_dien_thoai'] = $errors['so_dien_thoai'] ?? 'Email và số điện thoại đang trùng với hai khách hàng khác nhau. Vui lòng kiểm tra lại.';

            return [null, $errors];
        }

        return [$emailCustomer ?: $phoneCustomer, $errors];
    }

    private function clearForgotPasswordSession(): void
    {
        Session::forget([
            'customer_password_reset_payload',
            'customer_password_reset_otp_hash',
            'customer_password_reset_otp_expires_at',
            'customer_password_reset_verified',
        ]);
    }

    private function sendRegisterOtpEmail(string $email, string $otp): void
    {
        $defaultMailer = (string) config('mail.default', 'log');
        $smtpUsername = (string) config('mail.mailers.smtp.username', '');
        $fromAddress = (string) config('mail.from.address', '');

        if ($defaultMailer === 'log' || blank($smtpUsername) || blank($fromAddress)) {
            throw new \RuntimeException('Hệ thống chưa được cấu hình SMTP để gửi email thật.');
        }

        Mail::raw(
            "Mã OTP đăng ký tài khoản của bạn là: {$otp}. Mã có hiệu lực trong 5 phút.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Mã OTP đăng ký tài khoản');
            }
        );
    }

    private function sendForgotPasswordOtpEmail(string $email, string $otp): void
    {
        $defaultMailer = (string) config('mail.default', 'log');
        $smtpUsername = (string) config('mail.mailers.smtp.username', '');
        $fromAddress = (string) config('mail.from.address', '');

        if ($defaultMailer === 'log' || blank($smtpUsername) || blank($fromAddress)) {
            throw new \RuntimeException('Hệ thống chưa được cấu hình SMTP để gửi email thật.');
        }

        Mail::raw(
            "Mã OTP quên mật khẩu của bạn là: {$otp}. Mã có hiệu lực trong 5 phút.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Mã OTP đặt lại mật khẩu');
            }
        );
    }

    public function submitLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required|string',
        ]);

        $khachHang = KhachHang::query()->where('Email', $validated['email'])->first();

        if (!$khachHang || !Hash::check($validated['mat_khau'], $khachHang->MatKhau)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput();
        }

        if ((int) $khachHang->TrangThai === 0) {
            return back()->withErrors(['email' => 'Tài khoản khách hàng đang bị khóa.'])->withInput();
        }

        Session::put('customer_user_id', $khachHang->MaKH);
        Session::put('customer_user_name', $khachHang->TenKH);
        Session::put('customer_user_phone', $khachHang->SDT);
        Session::put('customer_user_email', $khachHang->Email);

        return redirect()->route('customer.index')->with('success', 'Đăng nhập khách hàng thành công.');
    }

    public function logout(): RedirectResponse
    {
        Session::forget(['customer_user_id', 'customer_user_name', 'customer_user_phone', 'customer_user_email']);

        return redirect()->route('customer.index')->with('success', 'Bạn đã đăng xuất khỏi tài khoản khách hàng.');
    }

    public function roomDetail(int|string $maLoai): View
    {
        $phong = LoaiPhong::query()
            ->with(['anhPhongs' => function ($query) {
                $query->select(['MaAP', 'MaLoai', 'HinhAnh'])->orderBy('MaAP');
            }])
            ->select(['MaLoai', 'TenLoai', 'GiaPhong', 'SoLuongNguoi', 'HinhAnh', 'MoTa'])
            ->findOrFail($maLoai);

        $relatedRooms = LoaiPhong::query()
            ->select(['MaLoai', 'TenLoai', 'GiaPhong', 'SoLuongNguoi', 'HinhAnh'])
            ->where('MaLoai', '<>', $phong->MaLoai)
            ->orderBy('GiaPhong')
            ->limit(3)
            ->get();

        $customerProfile = $this->getCustomerProfile();
        $paymentInfo = $this->getPaymentInfo();

        return view('customer.room-detail', compact('phong', 'relatedRooms', 'customerProfile', 'paymentInfo'));
    }

    public function tourDetail(string $maTour): View
    {
        $tour = Tour::query()
            ->with(['anhTours' => function ($query) {
                $query->select(['MaAT', 'MaTour', 'HinhAnh'])->orderBy('MaAT');
            }])
            ->select([
                'MaTour',
                'TenTour',
                'GiaTourNguoiLon',
                'GiaTourTreEm',
                'ThoiLuong',
                'DiaDiemKhoiHanh',
                'SoLuongKhachToiDa',
                'HinhAnh',
                'MoTa',
                'LichTrinh',
                'TrangThai',
            ])
            ->where('TrangThai', 1)
            ->findOrFail($maTour);

        $relatedTours = Tour::query()
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon', 'DiaDiemKhoiHanh', 'HinhAnh'])
            ->where('TrangThai', 1)
            ->where('MaTour', '<>', $tour->MaTour)
            ->orderBy('TenTour')
            ->limit(3)
            ->get();

        $customerProfile = $this->getCustomerProfile();
        $paymentInfo = $this->getPaymentInfo();

        return view('customer.tour-detail', compact('tour', 'relatedTours', 'customerProfile', 'paymentInfo'));
    }

    public function serviceDetail(string $maDV): View
    {
        $dichVu = DichVu::query()
            ->with(['anhDichVus' => function ($query) {
                $query->select(['MaADV', 'MaDV', 'HinhAnh'])->orderBy('MaADV');
            }])
            ->select(['MaDV', 'TenDV', 'GiaDV', 'TrangThai'])
            ->where('TrangThai', 1)
            ->findOrFail($maDV);

        $relatedServices = DichVu::query()
            ->select(['MaDV', 'TenDV', 'GiaDV'])
            ->where('TrangThai', 1)
            ->where('MaDV', '<>', $dichVu->MaDV)
            ->orderBy('TenDV')
            ->limit(3)
            ->get();

        $customerProfile = $this->getCustomerProfile();
        $paymentInfo = $this->getPaymentInfo();

        return view('customer.service-detail', compact('dichVu', 'relatedServices', 'customerProfile', 'paymentInfo'));
    }

    private function getCustomerProfile(): ?KhachHang
    {
        $customerId = Session::get('customer_user_id');

        if (!$customerId) {
            return null;
        }

        return KhachHang::query()
            ->select(['MaKH', 'TenKH', 'GioiTinh', 'SDT', 'Email'])
            ->find($customerId);
    }

    private function getPaymentInfo(): array
    {
        $nextInvoiceId = ((int) (HoaDon::query()->max('MaHD') ?? 0)) + 1;

        return [
            'bank_bin' => env('PAYMENT_BANK_BIN', '970422'),
            'bank_name' => env('PAYMENT_BANK_NAME', 'MB'),
            'account_no' => env('PAYMENT_ACCOUNT_NO', '0358178132'),
            'account_name' => env('PAYMENT_ACCOUNT_NAME', 'NGUYEN THAI HOC'),
            'transfer_note_prefix' => 'DATDICHVU-HD',
            'next_invoice_id' => $nextInvoiceId,
            'qr_template' => env('PAYMENT_QR_TEMPLATE', 'compact2'),
        ];
    }

    private function getCartItems(): array
    {
        $cartItems = Session::get('customer_cart', []);

        if (!is_array($cartItems)) {
            return [];
        }

        return array_values(array_filter($cartItems, static function ($item) {
            return is_array($item) && !empty($item['id']) && !empty($item['type']);
        }));
    }

    private function buildCartSummary(array $cartItems): array
    {
        $total = array_reduce($cartItems, static function (float $carry, array $item): float {
            return $carry + (float) ($item['estimated_total'] ?? 0);
        }, 0.0);

        return [
            'count' => count($cartItems),
            'total' => $total,
        ];
    }

    private function storeGuestCheckoutProfile(array $validated): void
    {
        Session::put('customer_guest_profile', [
            'ho_ten' => trim((string) ($validated['ho_ten'] ?? '')),
            'so_dien_thoai' => trim((string) ($validated['so_dien_thoai'] ?? '')),
            'email' => trim((string) ($validated['email'] ?? '')),
        ]);
    }

    private function resolveCheckoutCustomer(array $validated): array
    {
        $this->storeGuestCheckoutProfile($validated);

        $customerId = Session::get('customer_user_id');
        if (!empty($customerId)) {
            return [
                'customer_id' => (string) $customerId,
                'is_authenticated' => true,
                'was_created' => false,
            ];
        }

        $hoTen = trim((string) ($validated['ho_ten'] ?? ''));
        $soDienThoai = trim((string) ($validated['so_dien_thoai'] ?? ''));
        $email = Str::lower(trim((string) ($validated['email'] ?? '')));

        $emailCustomer = KhachHang::query()
            ->where('Email', $email)
            ->first();

        $phoneCustomer = KhachHang::query()
            ->where('SDT', $soDienThoai)
            ->first();

        if ($emailCustomer && $phoneCustomer && (string) $emailCustomer->MaKH !== (string) $phoneCustomer->MaKH) {
            throw ValidationException::withMessages([
                'email' => 'Email và số điện thoại đang thuộc về hai khách hàng khác nhau. Vui lòng kiểm tra lại.',
                'so_dien_thoai' => 'Email và số điện thoại đang thuộc về hai khách hàng khác nhau. Vui lòng kiểm tra lại.',
            ]);
        }

        $khachHang = $emailCustomer ?: $phoneCustomer;

        if ($khachHang) {
            $updates = [];

            if (blank($khachHang->TenKH)) {
                $updates['TenKH'] = $hoTen;
            }
            if (blank($khachHang->SDT)) {
                $updates['SDT'] = $soDienThoai;
            }
            if (blank($khachHang->Email)) {
                $updates['Email'] = $email;
            }
            if (blank($khachHang->MatKhau)) {
                $updates['MatKhau'] = Hash::make(Str::random(32));
            }

            if (!empty($updates)) {
                $khachHang->update($updates);
            }

            return [
                'customer_id' => (string) $khachHang->MaKH,
                'is_authenticated' => false,
                'was_created' => false,
            ];
        }

        $khachHang = KhachHang::query()->create([
            'TenKH' => $hoTen,
            'SDT' => $soDienThoai,
            'Email' => $email,
            'MatKhau' => Hash::make(Str::random(32)),
            'TrangThai' => 0,
        ]);

        return [
            'customer_id' => (string) $khachHang->MaKH,
            'is_authenticated' => false,
            'was_created' => true,
        ];
    }

    private function buildBookingItem(array $validated): array
    {
        $type = (string) ($validated['loai_dich_vu'] ?? '');
        $commonPayload = [
            'loai_dich_vu' => $type,
            'ma_dich_vu' => (string) ($validated['ma_dich_vu'] ?? ''),
            'ngay_su_dung' => $validated['ngay_su_dung'] ?? null,
            'ngay_nhan_phong' => $validated['ngay_nhan_phong'] ?? null,
            'ngay_tra_phong' => $validated['ngay_tra_phong'] ?? null,
            'ma_lich_khoi_hanh' => $validated['ma_lich_khoi_hanh'] ?? null,
            'so_luong_khach' => (int) ($validated['so_luong_khach'] ?? 1),
            'so_nguoi_lon' => (int) ($validated['so_nguoi_lon'] ?? 0),
            'so_tre_em' => (int) ($validated['so_tre_em'] ?? 0),
            'ghi_chu' => $validated['ghi_chu'] ?? null,
        ];

        if ($type === 'dich-vu') {
            $dichVu = DichVu::query()
                ->where('TrangThai', 1)
                ->find($commonPayload['ma_dich_vu']);

            if (!$dichVu) {
                throw ValidationException::withMessages([
                    'ma_dich_vu' => 'Dịch vụ đã chọn không tồn tại hoặc đang tạm ngưng.',
                ]);
            }

            $soLuong = max(1, (int) $commonPayload['so_luong_khach']);
            $unitPrice = (float) ($dichVu->GiaDV ?? 0);

            return [
                'id' => (string) Str::uuid(),
                'type' => 'dich-vu',
                'service_code' => (string) $dichVu->MaDV,
                'service_name' => (string) $dichVu->TenDV,
                'unit_price' => $unitPrice,
                'quantity_label' => $soLuong . ' số lượng',
                'schedule_label' => 'Ngày sử dụng: ' . Carbon::parse($commonPayload['ngay_su_dung'])->format('d/m/Y'),
                'estimated_total' => $unitPrice * $soLuong,
                'payload' => array_merge($commonPayload, [
                    'so_luong_khach' => $soLuong,
                ]),
            ];
        }

        if ($type === 'phong') {
            $maLoai = $commonPayload['ma_dich_vu'];
            $phong = LoaiPhong::query()
                ->select(['MaLoai', 'TenLoai', 'GiaPhong', 'SoLuongNguoi', 'HinhAnh', 'MoTa'])
                ->find($maLoai);

            if (!$phong) {
                throw ValidationException::withMessages([
                    'ma_dich_vu' => 'Loại phòng đã chọn không tồn tại.',
                ]);
            }

            $availableRoom = $this->findSmallestAvailableRoomByDateRange(
                $maLoai,
                (string) $commonPayload['ngay_nhan_phong'],
                (string) $commonPayload['ngay_tra_phong']
            );

            if (!$availableRoom) {
                throw ValidationException::withMessages([
                    'ma_dich_vu' => 'Không còn phòng trống loại này trong khoảng thời gian đã chọn.',
                ]);
            }

            $soDem = max(
                1,
                Carbon::parse((string) $commonPayload['ngay_nhan_phong'])
                    ->diffInDays(Carbon::parse((string) $commonPayload['ngay_tra_phong']))
            );
            $unitPrice = (float) ($phong->GiaPhong ?? 0);

            return [
                'id' => (string) Str::uuid(),
                'type' => 'phong',
                'service_code' => (string) $phong->MaLoai,
                'service_name' => (string) $phong->TenLoai,
                'unit_price' => $unitPrice,
                'quantity_label' => $soDem . ' đêm',
                'schedule_label' => 'Nhận ' . Carbon::parse((string) $commonPayload['ngay_nhan_phong'])->format('d/m/Y') . ' · Trả ' . Carbon::parse((string) $commonPayload['ngay_tra_phong'])->format('d/m/Y'),
                'estimated_total' => $unitPrice * $soDem,
                'payload' => $commonPayload,
            ];
        }

        if ($type === 'tour') {
            $maLichKhoiHanh = (string) ($commonPayload['ma_lich_khoi_hanh'] ?? '');
            $lichKhoiHanh = LichKhoiHanh::query()
                ->with('tour:MaTour,TenTour,GiaTourNguoiLon,GiaTourTreEm')
                ->find($maLichKhoiHanh);

            if (!$lichKhoiHanh || (string) $lichKhoiHanh->MaTour !== (string) $commonPayload['ma_dich_vu']) {
                throw ValidationException::withMessages([
                    'ma_lich_khoi_hanh' => 'Lịch khởi hành không hợp lệ cho tour đã chọn.',
                ]);
            }

            $soNguoiLon = max(0, (int) $commonPayload['so_nguoi_lon']);
            $soTreEm = max(0, (int) $commonPayload['so_tre_em']);
            $tongNguoi = $soNguoiLon + $soTreEm;

            if ($tongNguoi <= 0) {
                throw ValidationException::withMessages([
                    'so_nguoi_lon' => 'Số lượng khách tham gia tour phải lớn hơn 0.',
                ]);
            }

            if ($tongNguoi > (int) $lichKhoiHanh->SoChoConLai) {
                throw ValidationException::withMessages([
                    'ma_lich_khoi_hanh' => 'Số chỗ còn lại không đủ cho lựa chọn hiện tại.',
                ]);
            }

            $tour = $lichKhoiHanh->tour;
            $giaNguoiLon = (float) ($tour->GiaTourNguoiLon ?? 0);
            $giaTreEm = (float) ($tour->GiaTourTreEm ?? 0);

            return [
                'id' => (string) Str::uuid(),
                'type' => 'tour',
                'service_code' => (string) $tour->MaTour,
                'service_name' => (string) $tour->TenTour,
                'unit_price' => $giaNguoiLon,
                'quantity_label' => $soNguoiLon . ' NL, ' . $soTreEm . ' TE',
                'schedule_label' => 'Khởi hành ' . Carbon::parse((string) $lichKhoiHanh->NgayKhoiHanh)->format('d/m/Y') . ' · Kết thúc ' . Carbon::parse((string) $lichKhoiHanh->NgayKetThuc)->format('d/m/Y'),
                'estimated_total' => ($soNguoiLon * $giaNguoiLon) + ($soTreEm * $giaTreEm),
                'payload' => array_merge($commonPayload, [
                    'so_nguoi_lon' => $soNguoiLon,
                    'so_tre_em' => $soTreEm,
                ]),
            ];
        }

        throw ValidationException::withMessages([
            'loai_dich_vu' => 'Loại dịch vụ không hợp lệ.',
        ]);
    }

    private function createInvoiceFromBookingItems($customerId, array $bookingItems, int $paymentValue): HoaDon
    {
        return DB::transaction(function () use ($customerId, $bookingItems, $paymentValue) {
            $hoaDon = HoaDon::create([
                'MaKH' => $customerId,
                'NgayTao' => now(),
                'ThanhTien' => 0,
                'TrangThai' => 1,
                'ThanhToan' => $paymentValue,
            ]);

            foreach ($bookingItems as $bookingItem) {
                $payload = $bookingItem['payload'] ?? [];
                $type = $bookingItem['type'] ?? ($payload['loai_dich_vu'] ?? null);

                if ($type === 'phong') {
                    $maPhong = $this->findSmallestAvailableRoomByDateRange(
                        (string) ($payload['ma_dich_vu'] ?? ''),
                        (string) ($payload['ngay_nhan_phong'] ?? ''),
                        (string) ($payload['ngay_tra_phong'] ?? '')
                    );

                    if (!$maPhong) {
                        throw ValidationException::withMessages([
                            'ma_dich_vu' => 'Không còn phòng trống cho lựa chọn trong giỏ hàng.',
                        ]);
                    }

                    $phong = Phong::query()
                        ->with('loaiPhong:MaLoai,GiaPhong')
                        ->findOrFail($maPhong);
                    $soDem = max(
                        1,
                        Carbon::parse((string) ($payload['ngay_nhan_phong'] ?? now()->toDateString()))
                            ->diffInDays(Carbon::parse((string) ($payload['ngay_tra_phong'] ?? now()->addDay()->toDateString())))
                    );

                    HDPhong::create([
                        'MaHD' => $hoaDon->MaHD,
                        'MaPhong' => $maPhong,
                        'NgayNhanPhong' => $payload['ngay_nhan_phong'],
                        'NgayTraPhong' => $payload['ngay_tra_phong'],
                        'TongTien' => ((float) ($phong->GiaPhong ?? 0)) * $soDem,
                        'TrangThai' => 1,
                        'ThanhToan' => $paymentValue,
                    ]);

                    continue;
                }

                if ($type === 'dich-vu') {
                    $dichVu = DichVu::query()
                        ->where('TrangThai', 1)
                        ->findOrFail($payload['ma_dich_vu'] ?? null);
                    $soLuong = max(1, (int) ($payload['so_luong_khach'] ?? 1));

                    HDDichVu::create([
                        'MaHD' => $hoaDon->MaHD,
                        'MaDV' => $dichVu->MaDV,
                        'SoLuong' => $soLuong,
                        'NgaySuDung' => $payload['ngay_su_dung'] ?? null,
                        'TongTien' => ((float) ($dichVu->GiaDV ?? 0)) * $soLuong,
                        'TrangThai' => 1,
                        'ThanhToan' => $paymentValue,
                    ]);

                    continue;
                }

                if ($type === 'tour') {
                    $lichKhoiHanh = LichKhoiHanh::query()
                        ->with('tour:MaTour,TenTour,GiaTourNguoiLon,GiaTourTreEm')
                        ->lockForUpdate()
                        ->findOrFail($payload['ma_lich_khoi_hanh'] ?? null);

                    if ((string) $lichKhoiHanh->MaTour !== (string) ($payload['ma_dich_vu'] ?? '')) {
                        throw ValidationException::withMessages([
                            'ma_lich_khoi_hanh' => 'Lịch khởi hành trong giỏ hàng không còn hợp lệ.',
                        ]);
                    }

                    $soNguoiLon = max(0, (int) ($payload['so_nguoi_lon'] ?? 0));
                    $soTreEm = max(0, (int) ($payload['so_tre_em'] ?? 0));
                    $tongNguoi = $soNguoiLon + $soTreEm;

                    if ($tongNguoi <= 0 || $tongNguoi > (int) $lichKhoiHanh->SoChoConLai) {
                        throw ValidationException::withMessages([
                            'ma_lich_khoi_hanh' => 'Số chỗ tour không còn đủ để hoàn tất thanh toán.',
                        ]);
                    }

                    $tour = $lichKhoiHanh->tour;
                    $tongTienTour = ($soNguoiLon * (float) ($tour->GiaTourNguoiLon ?? 0))
                        + ($soTreEm * (float) ($tour->GiaTourTreEm ?? 0));

                    HDTOUR::create([
                        'MaHD' => $hoaDon->MaHD,
                        'MaLKH' => $lichKhoiHanh->MaLKH,
                        'SoNguoiLon' => $soNguoiLon,
                        'SoTreEm' => $soTreEm,
                        'TongTien' => $tongTienTour,
                        'TrangThai' => 1,
                        'ThanhToan' => $paymentValue,
                    ]);

                    $lichKhoiHanh->decrement('SoChoConLai', $tongNguoi);
                }
            }

            HoaDon::recalculateThanhTien($hoaDon->MaHD);

            return $hoaDon->fresh() ?? $hoaDon;
        });
    }

    public function removeCartItem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|string',
        ]);

        $cartItems = array_values(array_filter($this->getCartItems(), function (array $item) use ($validated) {
            return (string) ($item['id'] ?? '') !== (string) $validated['item_id'];
        }));

        Session::put('customer_cart', $cartItems);

        return redirect()->route('customer.cart')->with('success', 'Đã xóa dịch vụ khỏi giỏ hàng.');
    }

    public function checkoutCart(Request $request): RedirectResponse
    {
        $cartItems = $this->getCartItems();
        if (empty($cartItems)) {
            return redirect()->route('customer.cart')->withErrors([
                'error' => 'Giỏ hàng đang trống, vui lòng thêm dịch vụ trước khi thanh toán.',
            ]);
        }

        $validated = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'payment_method' => 'required|in:online,counter',
            'payment_verified' => 'nullable|in:0,1',
            'payment_transfer_note' => 'nullable|string|max:200',
        ]);

        $checkoutCustomer = $this->resolveCheckoutCustomer($validated);
        $customerId = (string) ($checkoutCustomer['customer_id'] ?? '');
        $isAuthenticatedCustomer = (bool) ($checkoutCustomer['is_authenticated'] ?? false);

        if ($validated['payment_method'] === 'online') {
            $isPaid = (
                (string) ($validated['payment_verified'] ?? '0') === '1'
                && !empty($validated['payment_transfer_note'])
                && $this->findMatchedPaymentRecord((string) $validated['payment_transfer_note']) !== null
            );

            if (!$isPaid) {
                return redirect()->route('customer.cart')->withErrors([
                    'error' => 'Vui lòng xác nhận thanh toán online thành công trước khi hoàn tất giỏ hàng.',
                ]);
            }
        }

        try {
            $normalizedItems = [];
            foreach ($cartItems as $cartItem) {
                $payload = $cartItem['payload'] ?? null;
                if (!is_array($payload)) {
                    continue;
                }

                $normalizedItems[] = $this->buildBookingItem($payload);
            }

            if (empty($normalizedItems)) {
                return redirect()->route('customer.cart')->withErrors([
                    'error' => 'Không có mục hợp lệ trong giỏ hàng để thanh toán.',
                ]);
            }

            $paymentValue = $validated['payment_method'] === 'online' ? 1 : 0;
            $hoaDon = $this->createInvoiceFromBookingItems($customerId, $normalizedItems, $paymentValue);

            Session::forget('customer_cart');

            $message = $paymentValue === 1
                ? 'Thanh toán giỏ hàng thành công.'
                : 'Đã lưu hóa đơn giỏ hàng ở trạng thái chưa thanh toán để thanh toán tại quầy.';

            if ($isAuthenticatedCustomer) {
                return redirect()
                    ->route('customer.invoices.show', $hoaDon->MaHD)
                    ->with('success', $message);
            }

            return redirect()
                ->route('customer.cart')
                ->with('success', $message . ' Mã hóa đơn của bạn là #' . $hoaDon->MaHD . '.');
        } catch (ValidationException $e) {
            return redirect()->route('customer.cart')->withErrors($e->errors());
        } catch (\Throwable $e) {
            Log::error('Customer cart checkout failed', [
                'message' => $e->getMessage(),
                'customer_id' => $customerId,
            ]);

            return redirect()->route('customer.cart')->withErrors([
                'error' => 'Không thể xử lý giỏ hàng lúc này. Vui lòng thử lại.',
            ]);
        }
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $loaiDichVu = $request->input('loai_dich_vu');
        $customerId = Session::get('customer_user_id');

        $rules = [
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'loai_dich_vu' => 'required|in:dich-vu,phong,tour',
            'ma_dich_vu' => 'required|string|max:30',
            'so_luong_khach' => 'nullable|integer|min:1|max:50',
            'ghi_chu' => 'nullable|string|max:500',
            'booking_action' => 'nullable|in:add_to_cart,book_now',
            'payment_method' => 'nullable|in:online,counter',
            'payment_verified' => 'nullable|in:0,1',
            'payment_transfer_note' => 'nullable|string|max:200',
        ];

        if ($loaiDichVu === 'phong') {
            $rules['ngay_nhan_phong'] = 'required|date|after_or_equal:today';
            $rules['ngay_tra_phong'] = 'required|date|after:ngay_nhan_phong';
        } elseif ($loaiDichVu === 'tour') {
            $rules['ma_lich_khoi_hanh'] = 'required|string|max:30';
            $rules['so_nguoi_lon'] = 'required|integer|min:0|max:50';
            $rules['so_tre_em'] = 'required|integer|min:0|max:50';
        } else {
            $rules['ngay_su_dung'] = 'required|date|after_or_equal:today';
        }

        $validated = $request->validate($rules);
        $validated['booking_action'] = $validated['booking_action'] ?? 'book_now';
        $validated['payment_method'] = $validated['payment_method'] ?? 'online';

        try {
            $this->storeGuestCheckoutProfile($validated);
            $bookingItem = $this->buildBookingItem($validated);

            if ($validated['booking_action'] === 'add_to_cart') {
                $cartItems = $this->getCartItems();
                $cartItems[] = $bookingItem;
                Session::put('customer_cart', $cartItems);

                $message = 'Đã thêm dịch vụ vào giỏ hàng của bạn.';
                $cartSummary = $this->buildCartSummary($cartItems);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'cart_count' => $cartSummary['count'],
                        'cart_total' => $cartSummary['total'],
                    ]);
                }

                return redirect()
                    ->route('customer.cart')
                    ->with('success', $message);
            }

            $checkoutCustomer = $this->resolveCheckoutCustomer($validated);
            $customerId = (string) ($checkoutCustomer['customer_id'] ?? '');
            $isAuthenticatedCustomer = (bool) ($checkoutCustomer['is_authenticated'] ?? false);

            if ($validated['payment_method'] === 'online') {
                $isPaid = (
                    (string) ($validated['payment_verified'] ?? '0') === '1'
                    && !empty($validated['payment_transfer_note'])
                    && $this->findMatchedPaymentRecord((string) $validated['payment_transfer_note']) !== null
                );

                if (!$isPaid) {
                    return back()->withErrors([
                        'error' => 'Vui lòng xác nhận thanh toán online thành công trước khi gửi yêu cầu đặt dịch vụ.',
                    ])->withInput();
                }
            }

            $paymentValue = $validated['payment_method'] === 'online' ? 1 : 0;
            $hoaDon = $this->createInvoiceFromBookingItems($customerId, [$bookingItem], $paymentValue);

            $message = $paymentValue === 1
                ? 'Đặt dịch vụ thành công và hệ thống đã ghi nhận thanh toán online.'
                : 'Đặt dịch vụ thành công. Hóa đơn hiện đang ở trạng thái chưa thanh toán để xử lý tại quầy.';

            if ($isAuthenticatedCustomer) {
                return redirect()
                    ->route('customer.invoices.show', $hoaDon->MaHD)
                    ->with('success', $message);
            }

            return redirect()
                ->route('customer.booking')
                ->with('success', $message . ' Mã hóa đơn của bạn là #' . $hoaDon->MaHD . '.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Customer booking failed', [
                'message' => $e->getMessage(),
                'customer_id' => $customerId,
                'loai_dich_vu' => $loaiDichVu,
            ]);

            return back()->withErrors([
                'error' => 'Không thể xử lý yêu cầu đặt dịch vụ lúc này. Vui lòng thử lại.',
            ])->withInput();
        }
    }

    /**
     * Check available rooms for date range (AJAX)
     * 
     */
    public function checkAvailableRooms(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ma_loai' => 'required|exists:tbl_LoaiPhong,MaLoai',
            'ngay_nhan' => 'required|date',
            'ngay_tra' => 'required|date|after:ngay_nhan',
        ]);

        $availableRooms = $this->getAvailableRoomsByDateRange(
            (string) $validated['ma_loai'],
            $validated['ngay_nhan'],
            $validated['ngay_tra']
        );

        return response()->json([
            'available' => count($availableRooms) > 0,
            'room_count' => count($availableRooms),
            'available_rooms' => $availableRooms,
        ]);
    }

    /**
     * Tìm phòng có mã nhỏ nhất trống trong khoảng ngày nhận/trả
     * 
     */
    private function findSmallestAvailableRoomByDateRange(int|string $maLoai, string $ngayNhan, string $ngayTra): ?string
    {
        $allRooms = Phong::query()
            ->select(['MaPhong'])
            ->where('MaLoai', $maLoai)
            ->orderBy('MaPhong', 'asc')
            ->get()
            ->pluck('MaPhong')
            ->toArray();

        if (empty($allRooms)) {
            return null;
        }

        foreach ($allRooms as $maPhong) {
            if ($this->isRoomAvailable($maPhong, $ngayNhan, $ngayTra)) {
                return $maPhong;
            }
        }

        return null;
    }

    /**
     * Get list of available rooms for date range
     * 
     */
    private function getAvailableRoomsByDateRange(int|string $maLoai, string $ngayNhan, string $ngayTra): array
    {
        $allRooms = Phong::query()
            ->select(['MaPhong'])
            ->where('MaLoai', $maLoai)
            ->orderBy('MaPhong', 'asc')
            ->get()
            ->pluck('MaPhong')
            ->toArray();

        $availableRooms = [];
        foreach ($allRooms as $maPhong) {
            if ($this->isRoomAvailable($maPhong, $ngayNhan, $ngayTra)) {
                $availableRooms[] = $maPhong;
            }
        }

        return $availableRooms;
    }

    /**
     * Check if a specific room is available for date range
     * 
     */
    private function isRoomAvailable(string $maPhong, string $ngayNhan, string $ngayTra): bool
    {
        $conflict = HDPhong::query()
            ->where('MaPhong', $maPhong)
            ->where('NgayNhanPhong', '<', $ngayTra)
            ->where('NgayTraPhong', '>', $ngayNhan)
            ->where('TrangThai', 1)
            ->exists();

        return !$conflict;
    }

    public function sepayWebhook(Request $request): JsonResponse
    {
        if ($request->filled('transfer_note')) {
            return $this->checkPaymentStatus($request);
        }

        $configuredApiKey = (string) env('SEPAY_WEBHOOK_API_KEY', '');
        $strictWebhookAuth = filter_var(env('SEPAY_WEBHOOK_STRICT_AUTH', false), FILTER_VALIDATE_BOOL);

        if ($configuredApiKey !== '') {
            $providedApiKey = (string) ($request->header('Authorization')
                ?: $request->header('X-API-KEY')
                ?: $request->header('X-SePay-Api-Key')
                ?: $request->input('api_key')
                ?: $request->input('apiKey'));

            $providedApiKey = trim($providedApiKey);
            if (preg_match('/^(Bearer|Apikey|ApiKey|Token)\s+/i', $providedApiKey) === 1) {
                $providedApiKey = preg_replace('/^(Bearer|Apikey|ApiKey|Token)\s+/i', '', $providedApiKey) ?? $providedApiKey;
            }

            if (!hash_equals($configuredApiKey, $providedApiKey)) {
                Log::warning('SePay webhook unauthorized', [
                    'path' => $request->path(),
                    'has_authorization' => $request->header('Authorization') !== null,
                    'has_x_api_key' => $request->header('X-API-KEY') !== null,
                    'has_x_sepay_api_key' => $request->header('X-SePay-Api-Key') !== null,
                ]);

                if (!$strictWebhookAuth) {
                    // Compatibility mode for providers that cannot send custom auth headers.
                } else {
                return response()->json([
                    'ok' => false,
                    'message' => 'Unauthorized webhook request.',
                ], 401);
                }
            }
        }

        $payload = $request->all();
        $dataPayload = is_array($payload['data'] ?? null) ? $payload['data'] : [];

        $record = [
            'received_at' => now()->toDateTimeString(),
            'source' => 'sepay',
            'content' => (string) (
                $payload['content']
                ?? $payload['description']
                ?? $payload['transferContent']
                ?? $payload['transaction_content']
                ?? $dataPayload['content']
                ?? $dataPayload['description']
                ?? $dataPayload['transferContent']
                ?? $dataPayload['transaction_content']
                ?? ''
            ),
            'amount' => (float) (
                $payload['amount']
                ?? $payload['transferAmount']
                ?? $payload['transfer_amount']
                ?? $dataPayload['amount']
                ?? $dataPayload['transferAmount']
                ?? $dataPayload['transfer_amount']
                ?? 0
            ),
            'transaction_time' => (string) (
                $payload['transactionDate']
                ?? $payload['transaction_time']
                ?? $payload['time']
                ?? $dataPayload['transactionDate']
                ?? $dataPayload['transaction_time']
                ?? $dataPayload['time']
                ?? now()->toDateTimeString()
            ),
            'bank_account' => (string) (
                $payload['accountNumber']
                ?? $payload['account_no']
                ?? $dataPayload['accountNumber']
                ?? $dataPayload['account_no']
                ?? ''
            ),
            'raw' => $payload,
        ];

        File::append(
            storage_path('app/sepay_transactions.ndjson'),
            json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL
        );

        return response()->json([
            'ok' => true,
            'message' => 'Webhook received.',
        ]);
    }

    /**
     * Kiểm tra giao dịch đã nhận từ SePay webhook để xác minh thanh toán.
     */
    public function checkPaymentStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transfer_note' => 'required|string|max:200',
        ]);

        $matchedRecord = $this->findMatchedPaymentRecord((string) $validated['transfer_note']);

        if (is_array($matchedRecord)) {
            return response()->json([
                'paid'    => true,
                'message' => 'Xác nhận thanh toán thành công! Đang gửi yêu cầu đặt dịch vụ...',
                'amount'  => (float) ($matchedRecord['amount'] ?? 0),
                'when'    => $matchedRecord['transaction_time'] ?? ($matchedRecord['received_at'] ?? null),
            ]);
        }

        return response()->json([
            'paid'    => false,
            'message' => 'Chưa phát hiện biến động số dư với nội dung "' . $validated['transfer_note'] . '". Vui lòng kiểm tra lại hoặc thử sau vài giây.',
        ]);
    }

    private function findMatchedPaymentRecord(string $transferNote): ?array
    {
        $windowMinutes = (int) env('PAYMENT_CHECK_WINDOW_MINUTES', 30);
        $accountNo = env('PAYMENT_ACCOUNT_NO', '');
        $transferNote = Str::lower(trim($transferNote));
        $normalizedTransferNote = preg_replace('/[^a-z0-9]+/i', '', $transferNote) ?? '';
        $recordsFile = storage_path('app/sepay_transactions.ndjson');

        if (!File::exists($recordsFile)) {
            return null;
        }

        try {
            $lines = array_filter(explode(PHP_EOL, (string) File::get($recordsFile)));
            $cutoff = now()->subMinutes($windowMinutes);

            foreach (array_reverse($lines) as $line) {
                $record = json_decode($line, true);
                if (!is_array($record)) {
                    continue;
                }

                $receivedAt = Carbon::parse($record['received_at'] ?? now()->toDateTimeString());
                if ($receivedAt->lt($cutoff)) {
                    continue;
                }

                if (!empty($accountNo) && !empty($record['bank_account']) && (string) $record['bank_account'] !== (string) $accountNo) {
                    continue;
                }

                $desc = Str::lower(trim((string) ($record['content'] ?? '')));
                $normalizedDesc = preg_replace('/[^a-z0-9]+/i', '', $desc) ?? '';

                if (str_contains($desc, $transferNote) || (!empty($normalizedTransferNote) && str_contains($normalizedDesc, $normalizedTransferNote))) {
                    return $record;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    /**
     * Get available tour schedules for a specific tour (AJAX)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getTourSchedules(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ma_tour' => 'required|string|max:30',
        ]);

        $tour = Tour::query()
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon', 'GiaTourTreEm', 'ThoiLuong'])
            ->findOrFail($validated['ma_tour']);

        $lichKhoiHanhs = LichKhoiHanh::query()
            ->select(['MaLKH', 'NgayKhoiHanh', 'NgayKetThuc', 'SoChoConLai'])
            ->where('MaTour', $validated['ma_tour'])
            ->where('SoChoConLai', '>', 0)
            ->orderBy('NgayKhoiHanh')
            ->get()
            ->map(function ($lkh) use ($tour) {
                return [
                    'ma_lkh' => $lkh->MaLKH,
                    'ngay_khoi_hanh' => filled($lkh->NgayKhoiHanh) ? Carbon::parse($lkh->NgayKhoiHanh)->format('d/m/Y') : '-',
                    'ngay_ket_thuc' => filled($lkh->NgayKetThuc) ? Carbon::parse($lkh->NgayKetThuc)->format('d/m/Y') : '-',
                    'so_cho_con_lai' => (int) $lkh->SoChoConLai,
                    'gia_nguoi_lon' => (float) $tour->GiaTourNguoiLon,
                    'gia_tre_em' => (float) $tour->GiaTourTreEm,
                ];
            });

        return response()->json([
            'success' => true,
            'tour_name' => $tour->TenTour,
            'schedules' => $lichKhoiHanhs,
        ]);
    }
}
