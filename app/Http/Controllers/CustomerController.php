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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $phongIds = Phong::query()
            ->selectRaw('MIN(MaPhong) as MaPhong')
            ->groupBy('TenPhong')
            ->orderBy('TenPhong')
            ->limit(6)
            ->pluck('MaPhong');

        $phongs = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'GiaPhong', 'HinhAnh', 'MoTa', 'MaLoai'])
            ->with('loaiPhong:MaLoai,TenLoai')
            ->whereIn('MaPhong', $phongIds)
            ->orderBy('TenPhong')
            ->get()
            ->values();

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

        $distinctRoomIds = Phong::query()
            ->selectRaw('MIN(MaPhong)')
            ->when(!empty($filters['q']), function ($query) use ($filters) {
                $query->where('TenPhong', 'like', '%' . $filters['q'] . '%');
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
            ->groupBy('TenPhong');

        $items = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'SoLuongNguoi', 'GiaPhong', 'HinhAnh', 'MoTa', 'MaLoai'])
            ->with('loaiPhong:MaLoai,TenLoai')
            ->whereIn('MaPhong', $distinctRoomIds)
            ->orderBy('GiaPhong')
            ->orderBy('TenPhong')
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
            'bank_bin' => env('PAYMENT_BANK_BIN', '970422'),
            'bank_name' => env('PAYMENT_BANK_NAME', 'MB'),
            'account_no' => env('PAYMENT_ACCOUNT_NO', '0358178132'),
            'account_name' => env('PAYMENT_ACCOUNT_NAME', 'NGUYEN THAI HOC'),
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

        $relatedRoomIds = Phong::query()
            ->selectRaw('MIN(MaPhong) as MaPhong')
            ->where('TenPhong', '<>', $phong->TenPhong)
            ->groupBy('TenPhong')
            ->orderByRaw('MIN(GiaPhong)')
            ->limit(3)
            ->pluck('MaPhong');

        $relatedRooms = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'GiaPhong'])
            ->whereIn('MaPhong', $relatedRoomIds)
            ->orderBy('GiaPhong')
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

        // Validate thuộc tính riêng của từng loại dịch vụ
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

        $windowMinutes = (int) env('PAYMENT_CHECK_WINDOW_MINUTES', 30);
        $accountNo = env('PAYMENT_ACCOUNT_NO', '');
        $transferNote = Str::lower(trim($validated['transfer_note']));
        $normalizedTransferNote = preg_replace('/[^a-z0-9]+/i', '', $transferNote) ?? '';
        $recordsFile = storage_path('app/sepay_transactions.ndjson');

        if (!File::exists($recordsFile)) {
            return response()->json([
                'paid'    => false,
                'message' => 'Chưa nhận được giao dịch nào từ SePay webhook. Vui lòng chuyển khoản và thử lại.',
            ]);
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
                    return response()->json([
                        'paid'    => true,
                        'message' => 'Xác nhận thanh toán thành công! Đang gửi yêu cầu đặt dịch vụ...',
                        'amount'  => (float) ($record['amount'] ?? 0),
                        'when'    => $record['transaction_time'] ?? ($record['received_at'] ?? null),
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
