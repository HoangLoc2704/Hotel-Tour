<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Models\HDDichVu;
use App\Models\HDTOUR;
use App\Models\HDPhong;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\LichKhoiHanh;
use App\Models\Phong;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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

        $phongs = Phong::query()
            ->select(['TenPhong', 'GiaPhong', 'HinhAnh', 'MoTa', 'MaLoai'])
            ->with('loaiPhong:MaLoai,TenLoai')
            ->orderBy('TenPhong')
            ->get()
            ->unique(function ($phong) {
                return mb_strtolower(trim((string) $phong->TenPhong));
            })
            ->take(6)
            ->values();

        $tours = Tour::query()
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon', 'DiaDiemKhoiHanh', 'ThoiLuong', 'HinhAnh', 'MoTa'])
            ->where('TrangThai', 1)
            ->orderBy('TenTour')
            ->limit(6)
            ->get();

        return view('customer.index', compact('dichVus', 'phongs', 'tours'));
    }

    public function booking(): View
    {
        $dichVus = DichVu::query()
            ->select(['MaDV', 'TenDV', 'GiaDV'])
            ->where('TrangThai', 1)
            ->orderBy('TenDV')
            ->get();

        $phongs = Phong::query()
            ->select(['TenPhong', 'GiaPhong'])
            ->orderBy('TenPhong')
            ->get()
            ->unique('TenPhong')
            ->values();

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
                'value' => $item->TenPhong,
                'label' => $item->TenPhong,
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

        $customerProfile = null;
        $customerId = Session::get('customer_user_id');
        if ($customerId) {
            $customerProfile = KhachHang::query()
                ->select(['MaKH', 'TenKH', 'SDT', 'Email'])
                ->find($customerId);
        }

        $paymentInfo = [
            'bank_bin' => env('PAYMENT_BANK_BIN', '970436'),
            'bank_name' => env('PAYMENT_BANK_NAME', 'Vietcombank'),
            'account_no' => env('PAYMENT_ACCOUNT_NO', '9857147907'),
            'account_name' => env('PAYMENT_ACCOUNT_NAME', 'TRINH HOANG LOC'),
            'transfer_note_prefix' => env('PAYMENT_TRANSFER_NOTE_PREFIX', 'DATDICHVU'),
            'qr_template' => env('PAYMENT_QR_TEMPLATE', 'compact2'),
        ];

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
            ->with([
                'hdPhongs.phong:MaPhong,TenPhong',
                'hdDichVus.dichVu:MaDV,TenDV',
                'hdTours.lichKhoiHanh:MaLKH,MaTour,NgayKhoiHanh,NgayKetThuc',
                'hdTours.lichKhoiHanh.tour:MaTour,TenTour',
            ])
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

        return view('customer.invoices', compact('hoaDons', 'filters'));
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
                'hdPhongs.phong:MaPhong,TenPhong,GiaPhong',
                'hdDichVus.dichVu:MaDV,TenDV,GiaDV',
                'hdTours.lichKhoiHanh:MaLKH,MaTour,NgayKhoiHanh,NgayKetThuc',
                'hdTours.lichKhoiHanh.tour:MaTour,TenTour,GiaTourNguoiLon,GiaTourTreEm',
            ])
            ->where('MaKH', $customerId)
            ->findOrFail($maHD);

        return view('customer.invoice-detail', compact('hoaDon'));
    }

    public function login(): View
    {
        return view('customer.login');
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

    public function roomDetail(string $tenPhong): View
    {
        $phong = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'GiaPhong', 'HinhAnh', 'MoTa', 'SoLuongNguoi', 'MaLoai'])
            ->with('loaiPhong:MaLoai,TenLoai')
            ->where('TenPhong', $tenPhong)
            ->firstOrFail();

        $relatedRooms = Phong::query()
            ->select(['TenPhong', 'GiaPhong'])
            ->where('TenPhong', '<>', $phong->TenPhong)
            ->orderBy('GiaPhong')
            ->limit(3)
            ->get();

        return view('customer.room-detail', compact('phong', 'relatedRooms'));
    }

    public function tourDetail(string $maTour): View
    {
        $tour = Tour::query()
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
            ->select(['MaTour', 'TenTour', 'GiaTourNguoiLon'])
            ->where('TrangThai', 1)
            ->where('MaTour', '<>', $tour->MaTour)
            ->orderBy('TenTour')
            ->limit(3)
            ->get();

        return view('customer.tour-detail', compact('tour', 'relatedTours'));
    }

    public function serviceDetail(string $maDV): View
    {
        $dichVu = DichVu::query()
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

        return view('customer.service-detail', compact('dichVu', 'relatedServices'));
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $loaiDichVu = $request->input('loai_dich_vu');
        $customerId = Session::get('customer_user_id');

        if (!$customerId) {
            return back()
                ->withErrors(['error' => 'Vui lòng đăng nhập tài khoản khách hàng trước khi đặt dịch vụ.'])
                ->withInput();
        }

        // Validation rules
        $rules = [
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'loai_dich_vu' => 'required|in:dich-vu,phong,tour',
            'ma_dich_vu' => 'required|string|max:30',
            'so_luong_khach' => 'nullable|integer|min:1|max:50',
            'ghi_chu' => 'nullable|string|max:500',
        ];

        // Add conditional validation for dates and service-specific fields
        if ($loaiDichVu === 'phong') {
            $rules['ngay_nhan_phong'] = 'required|date|after_or_equal:today';
            $rules['ngay_tra_phong'] = 'required|date|after:ngay_nhan_phong';
        } else if ($loaiDichVu === 'tour') {
            $rules['ma_lich_khoi_hanh'] = 'required|string|max:30';
            $rules['so_nguoi_lon'] = 'required|integer|min:0|max:50';
            $rules['so_tre_em'] = 'required|integer|min:0|max:50';
        } else {
            $rules['ngay_su_dung'] = 'required|date|after_or_equal:today';
        }

        $validated = $request->validate($rules);

        $maDichVu = $validated['ma_dich_vu'];

        // Nếu đặt phòng, tìm phòng có mã nhỏ nhất trống trong khoảng ngày
        if ($loaiDichVu === 'phong') {
            $maDichVu = $this->findSmallestAvailableRoomByDateRange(
                $validated['ma_dich_vu'],
                $validated['ngay_nhan_phong'],
                $validated['ngay_tra_phong']
            );
            
            if (!$maDichVu) {
                return back()->withErrors(['ma_dich_vu' => 'Không còn phòng trống loại này trong khoảng thời gian đã chọn.'])->withInput();
            }

            // Create invoice and detail
            try {
                $hoaDon = HoaDon::create([
                    'MaKH' => $customerId,
                    'NgayTao' => now(),
                    'ThanhTien' => 0,
                    'TrangThai' => 1,
                    'ThanhToan' => 0,
                ]);

                $phong = Phong::query()->findOrFail($maDichVu);
                $giaPhong = (float) ($phong->GiaPhong ?? 0);
                $soDem = max(
                    1,
                    Carbon::parse($validated['ngay_nhan_phong'])->diffInDays(Carbon::parse($validated['ngay_tra_phong']))
                );
                $tongTienPhong = $giaPhong * $soDem;

                HDPhong::create([
                    'MaHD' => $hoaDon->MaHD,
                    'MaPhong' => $maDichVu,
                    'NgayNhanPhong' => $validated['ngay_nhan_phong'],
                    'NgayTraPhong' => $validated['ngay_tra_phong'],
                    'TongTien' => $tongTienPhong,
                    'TrangThai' => 1,
                    'ThanhToan' => 0,
                ]);

                HoaDon::recalculateThanhTien($hoaDon->MaHD);

                return back()->with('success', 'Yêu cầu đặt phòng đã được ghi nhận. Chúng tôi sẽ liên hệ với bạn sớm nhất.');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Lỗi khi tạo đơn đặt phòng. Vui lòng thử lại.'])->withInput();
            }
        }

        if ($loaiDichVu === 'dich-vu') {
            try {
                $hoaDon = HoaDon::create([
                    'MaKH' => $customerId,
                    'NgayTao' => now(),
                    'ThanhTien' => 0,
                    'TrangThai' => 1,
                    'ThanhToan' => 0,
                ]);

                $dichVu = DichVu::query()->findOrFail($maDichVu);
                $soLuong = max(1, (int) ($validated['so_luong_khach'] ?? 1));
                $tongTienDichVu = (float) ($dichVu->GiaDV ?? 0) * $soLuong;

                HDDichVu::create([
                    'MaHD' => $hoaDon->MaHD,
                    'MaDV' => $maDichVu,
                    'SoLuong' => $soLuong,
                    'TongTien' => $tongTienDichVu,
                    'TrangThai' => 1,
                    'ThanhToan' => 0,
                ]);

                HoaDon::recalculateThanhTien($hoaDon->MaHD);

                return back()->with('success', 'Yêu cầu đặt dịch vụ đã được ghi nhận. Chúng tôi sẽ liên hệ với bạn sớm nhất.');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Lỗi khi tạo đơn dịch vụ. Vui lòng thử lại.'])->withInput();
            }
        }

        // Handle tour booking
        if ($loaiDichVu === 'tour') {
            try {
                $hoaDon = HoaDon::create([
                    'MaKH' => $customerId,
                    'NgayTao' => now(),
                    'ThanhTien' => 0,
                    'TrangThai' => 1,
                    'ThanhToan' => 0,
                ]);

                $maLKH = $validated['ma_lich_khoi_hanh'];
                $soNguoiLon = (int) ($validated['so_nguoi_lon'] ?? 0);
                $soTreEm = (int) ($validated['so_tre_em'] ?? 0);
                $soNguoiDat = $soNguoiLon + $soTreEm;

                // Get tour and schedule information to calculate price
                $lichKhoiHanh = LichKhoiHanh::query()->findOrFail($maLKH);
                $tour = $lichKhoiHanh->tour;

                if ($soNguoiDat <= 0) {
                    return back()->withErrors(['so_nguoi_lon' => 'Số lượng người đặt tour phải lớn hơn 0.'])->withInput();
                }

                if ($soNguoiDat > (int) $lichKhoiHanh->SoChoConLai) {
                    return back()->withErrors(['ma_lich_khoi_hanh' => 'Số chỗ còn lại không đủ cho số lượng khách đã chọn.'])->withInput();
                }

                $giaNguoiLon = (float) ($tour->GiaTourNguoiLon ?? 0);
                $giaTreEm = (float) ($tour->GiaTourTreEm ?? 0);
                $tongTienTour = ($soNguoiLon * $giaNguoiLon) + ($soTreEm * $giaTreEm);

                HDTOUR::create([
                    'MaHD' => $hoaDon->MaHD,
                    'MaLKH' => $maLKH,
                    'SoNguoiLon' => $soNguoiLon,
                    'SoTreEm' => $soTreEm,
                    'TongTien' => $tongTienTour,
                    'TrangThai' => 1,
                    'ThanhToan' => 0,
                ]);

                // Decrement available seats in tour schedule
                $lichKhoiHanh->decrement('SoChoConLai', $soNguoiDat);

                HoaDon::recalculateThanhTien($hoaDon->MaHD);

                return back()->with('success', 'Yêu cầu đặt tour du lịch đã được ghi nhận. Chúng tôi sẽ liên hệ với bạn sớm nhất.');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Lỗi khi tạo đơn tour du lịch. Vui lòng thử lại.'])->withInput();
            }
        }
    }

    /**
     * Check available rooms for date range (AJAX)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function checkAvailableRooms(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ten_phong' => 'required|string',
            'ngay_nhan' => 'required|date',
            'ngay_tra' => 'required|date|after:ngay_nhan',
        ]);

        $availableRooms = $this->getAvailableRoomsByDateRange(
            $validated['ten_phong'],
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
     * @param string $tenPhong Tên loại phòng
     * @param string $ngayNhan Ngày nhận phòng (YYYY-MM-DD)
     * @param string $ngayTra Ngày trả phòng (YYYY-MM-DD)
     * @return string|null Mã phòng nhỏ nhất trống, hoặc null nếu không tìm thấy
     */
    private function findSmallestAvailableRoomByDateRange(string $tenPhong, string $ngayNhan, string $ngayTra): ?string
    {
        $allRooms = Phong::query()
            ->select(['MaPhong'])
            ->where('TenPhong', $tenPhong)
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
     * @param string $tenPhong Tên loại phòng
     * @param string $ngayNhan Ngày nhận phòng (YYYY-MM-DD)
     * @param string $ngayTra Ngày trả phòng (YYYY-MM-DD)
     * @return array Danh sách mã phòng trống, sắp xếp theo mã
     */
    private function getAvailableRoomsByDateRange(string $tenPhong, string $ngayNhan, string $ngayTra): array
    {
        $allRooms = Phong::query()
            ->select(['MaPhong'])
            ->where('TenPhong', $tenPhong)
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
     * @param string $maPhong Mã phòng
     * @param string $ngayNhan Ngày nhận phòng (YYYY-MM-DD)
     * @param string $ngayTra Ngày trả phòng (YYYY-MM-DD)
     * @return bool True nếu phòng trống, false nếu bị đặt
     */
    private function isRoomAvailable(string $maPhong, string $ngayNhan, string $ngayTra): bool
    {
        // Overlap rule: [old_checkin, old_checkout) intersects [new_checkin, new_checkout)
        // to avoid blocking same-day check-out/check-in turnovers.
        $conflict = HDPhong::query()
            ->where('MaPhong', $maPhong)
            ->where('NgayNhanPhong', '<', $ngayTra)
            ->where('NgayTraPhong', '>', $ngayNhan)
            ->where('TrangThai', 1)
            ->exists();

        return !$conflict;
    }

    /**
     * Kiểm tra biến động số dư tài khoản qua Casso API để xác minh thanh toán.
     * Casso API docs: https://docs.casso.vn
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPaymentStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transfer_note' => 'required|string|max:200',
        ]);

        $cassoApiKey = env('CASSO_API_KEY', '');
        if (empty($cassoApiKey)) {
            return response()->json([
                'paid'    => false,
                'message' => 'Chưa cấu hình Casso API key. Vui lòng liên hệ quản trị viên.',
                'error'   => 'missing_api_key',
            ]);
        }

        $windowMinutes = (int) env('PAYMENT_CHECK_WINDOW_MINUTES', 30);
        $fromDate = now()->subMinutes($windowMinutes)->format('Y-m-d H:i:s');
        $toDate   = now()->addMinutes(1)->format('Y-m-d H:i:s');
        $accountNo = env('PAYMENT_ACCOUNT_NO', '');

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'apikey ' . $cassoApiKey,
            ])->get('https://oauth.casso.vn/v2/transactions', [
                'bankSubAccNo' => $accountNo,
                'fromDate'     => $fromDate,
                'toDate'       => $toDate,
                'page'         => 1,
                'pageSize'     => 50,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'paid'    => false,
                    'message' => 'Không thể kết nối Casso API (HTTP ' . $response->status() . '). Vui lòng thử lại.',
                    'error'   => 'casso_http_error',
                ]);
            }

            $body = $response->json();

            if (($body['error'] ?? -1) !== 0) {
                return response()->json([
                    'paid'    => false,
                    'message' => 'Casso API trả lỗi: ' . ($body['message'] ?? 'unknown'),
                    'error'   => 'casso_api_error',
                ]);
            }

            $records = $body['data']['records'] ?? [];
            $transferNote = strtolower(trim($validated['transfer_note']));

            foreach ($records as $record) {
                $desc = strtolower(trim($record['description'] ?? ''));
                // So khớp nội dung chuyển khoản (chứa chuỗi mã đặt)
                if (str_contains($desc, $transferNote)) {
                    return response()->json([
                        'paid'    => true,
                        'message' => 'Xác nhận thanh toán thành công! Đang gửi yêu cầu đặt dịch vụ...',
                        'amount'  => $record['amount'] ?? 0,
                        'when'    => $record['when'] ?? null,
                    ]);
                }
            }

            return response()->json([
                'paid'    => false,
                'message' => 'Chưa phát hiện biến động số dư với nội dung "' . $validated['transfer_note'] . '". Vui lòng kiểm tra lại hoặc thử sau vài giây.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'paid'    => false,
                'message' => 'Lỗi khi kiểm tra thanh toán. Vui lòng thử lại.',
                'error'   => 'exception',
            ]);
        }
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
                    'ngay_khoi_hanh' => $lkh->NgayKhoiHanh,
                    'ngay_ket_thuc' => $lkh->NgayKetThuc,
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
