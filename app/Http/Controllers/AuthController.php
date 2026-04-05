<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\Phong;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required|string',
        ]);

        // Tìm nhân viên theo email
        $nhanVien = NhanVien::with('chucVu')->where('Email', $request->email)->first();
        // Kiểm tra nhân viên tồn tại và mật khẩu đúng
        if ($nhanVien && Hash::check($request->mat_khau, $nhanVien->MatKhau)) {
            // Kiểm tra trạng thái tài khoản
            if ($nhanVien->TrangThai == 0) {
                return back()->withErrors(['email' => 'Tài khoản đã bị khóa'])->withInput();
            }

            // Lưu thông tin vào session
            Session::put('user_id', $nhanVien->MaNV);
            Session::put('user_name', $nhanVien->TenNV);
            Session::put('user_email', $nhanVien->Email);
            Session::put('user_role', $nhanVien->MaCV);
            Session::put('user_role_name', $nhanVien->chucVu->TenCV ?? '');

            // Chuyển hướng đến trang admin
            return redirect()->route('admin');
        }

        // Đăng nhập thất bại
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng'])->withInput();
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    public function index()
    {
        // Kiểm tra nếu đã đăng nhập thì chuyển sang admin
        if (Session::has('user_id')) {
            return redirect()->route('admin');
        }

        // Nếu chưa đăng nhập thì chuyển sang trang login
        return redirect()->route('login');
    }

    public function showAdmin(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $filters = $request->validate([
            'report_date' => 'nullable|date',
            'report_month' => ['nullable', 'regex:/^\d{4}-\d{2}$/'],
            'report_year' => 'nullable|integer|min:2000|max:2100',
        ]);

        $selectedDate = !empty($filters['report_date'])
            ? Carbon::parse($filters['report_date'])
            : now();

        $selectedMonth = !empty($filters['report_month'])
            ? Carbon::createFromFormat('Y-m', $filters['report_month'])->startOfMonth()
            : now()->startOfMonth();

        $selectedYear = !empty($filters['report_year'])
            ? Carbon::create((int) $filters['report_year'], 1, 1)->startOfYear()
            : now()->startOfYear();

        $user = [
            'name' => Session::get('user_name'),
            'email' => Session::get('user_email'),
            'role' => Session::get('user_role')
        ];

        $counts = [
            'nhan_vien' => NhanVien::count(),
            'khach_hang' => KhachHang::count(),
            'phong' => Phong::count(),
            'hoa_don' => HoaDon::count(),
        ];

        $revenueBaseQuery = HoaDon::query()
            ->where('TrangThai', 1)
            ->where('ThanhToan', 1);

        $revenueReport = [
            'day' => [
                'label' => $selectedDate->format('d/m/Y'),
                'total' => (float) (clone $revenueBaseQuery)
                    ->whereDate('NgayTao', $selectedDate->toDateString())
                    ->sum('ThanhTien'),
                'count' => (int) (clone $revenueBaseQuery)
                    ->whereDate('NgayTao', $selectedDate->toDateString())
                    ->count(),
            ],
            'month' => [
                'label' => 'Tháng ' . $selectedMonth->format('m/Y'),
                'total' => (float) (clone $revenueBaseQuery)
                    ->whereYear('NgayTao', $selectedMonth->year)
                    ->whereMonth('NgayTao', $selectedMonth->month)
                    ->sum('ThanhTien'),
                'count' => (int) (clone $revenueBaseQuery)
                    ->whereYear('NgayTao', $selectedMonth->year)
                    ->whereMonth('NgayTao', $selectedMonth->month)
                    ->count(),
            ],
            'year' => [
                'label' => 'Năm ' . $selectedYear->format('Y'),
                'total' => (float) (clone $revenueBaseQuery)
                    ->whereYear('NgayTao', $selectedYear->year)
                    ->sum('ThanhTien'),
                'count' => (int) (clone $revenueBaseQuery)
                    ->whereYear('NgayTao', $selectedYear->year)
                    ->count(),
            ],
        ];

        if ($request->ajax()) {
            return view('admin.partials.dashboard-content', compact('user', 'counts', 'revenueReport', 'filters'));
        }

        return view('admin', compact('user', 'counts', 'revenueReport', 'filters'));
    }

    public function testDb()
    {
        try {
            DB::connection()->getPdo();
            return response('Kết nối DB thành công');
        } catch (\Exception $e) {
            return response('Lỗi: ' . $e->getMessage(), 500);
        }
    }
}
