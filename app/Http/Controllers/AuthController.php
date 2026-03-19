<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use Illuminate\Http\Request;
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
        $nhanVien = NhanVien::where('Email', $request->email)->first();
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

    public function showAdmin()
    {
        // Kiểm tra đăng nhập
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $user = [
            'name' => Session::get('user_name'),
            'email' => Session::get('user_email'),
            'role' => Session::get('user_role')
        ];

        return view('admin', compact('user'));
    }
}
