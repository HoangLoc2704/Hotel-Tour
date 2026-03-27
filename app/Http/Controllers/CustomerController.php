<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Models\KhachHang;
use App\Models\Phong;
use App\Models\Tour;
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
            ->select(['MaPhong', 'TenPhong', 'GiaPhong', 'HinhAnh', 'MoTa', 'MaLoai'])
            ->with('loaiPhong:MaLoai,TenLoai')
            ->orderBy('TenPhong')
            ->limit(24)
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
            ->select(['MaDV', 'TenDV'])
            ->where('TrangThai', 1)
            ->orderBy('TenDV')
            ->get();

        $phongs = Phong::query()
            ->select(['MaPhong', 'TenPhong'])
            ->orderBy('TenPhong')
            ->limit(24)
            ->get()
            ->unique(function ($phong) {
                return mb_strtolower(trim((string) $phong->TenPhong));
            })
            ->values();

        $tours = Tour::query()
            ->select(['MaTour', 'TenTour'])
            ->where('TrangThai', 1)
            ->orderBy('TenTour')
            ->get();

        return view('customer.booking', compact('dichVus', 'phongs', 'tours'));
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
        Session::put('customer_user_email', $khachHang->Email);

        return redirect()->route('customer.index')->with('success', 'Đăng nhập khách hàng thành công.');
    }

    public function logout(): RedirectResponse
    {
        Session::forget(['customer_user_id', 'customer_user_name', 'customer_user_email']);

        return redirect()->route('customer.index')->with('success', 'Bạn đã đăng xuất khỏi tài khoản khách hàng.');
    }

    public function roomDetail(string $maPhong): View
    {
        $phong = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'GiaPhong', 'HinhAnh', 'MoTa', 'SoLuongNguoi', 'MaLoai'])
            ->with('loaiPhong:MaLoai,TenLoai')
            ->findOrFail($maPhong);

        $relatedRooms = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'GiaPhong'])
            ->where('MaPhong', '<>', $phong->MaPhong)
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
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'loai_dich_vu' => 'required|in:dich-vu,phong,tour',
            'ma_dich_vu' => 'required|string|max:30',
            'ngay_su_dung' => 'required|date|after_or_equal:today',
            'so_luong_khach' => 'required|integer|min:1|max:50',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        $record = [
            'ma_dat_cho' => 'BK' . now()->format('YmdHis') . random_int(10, 99),
            'created_at' => now()->toDateTimeString(),
            'ho_ten' => $validated['ho_ten'],
            'so_dien_thoai' => $validated['so_dien_thoai'],
            'email' => $validated['email'] ?? null,
            'loai_dich_vu' => $validated['loai_dich_vu'],
            'ma_dich_vu' => $validated['ma_dich_vu'],
            'ngay_su_dung' => $validated['ngay_su_dung'],
            'so_luong_khach' => (int) $validated['so_luong_khach'],
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => 'moi_tiep_nhan',
        ];

        $filePath = storage_path('app/customer_bookings.ndjson');
        File::ensureDirectoryExists(dirname($filePath));
        File::append($filePath, json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL);

        return back()->with('success', 'Yeu cau dat dich vu da duoc ghi nhan. Chung toi se lien he voi ban trong it phut.');
    }
}
