<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KhachHangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = KhachHang::query();
        if ($search) {
            $query->where('TenKH', 'like', "%{$search}%")
                  ->orWhere('TenTK', 'like', "%{$search}%");
        }
        $khachHang = $query->paginate(10);
        return view('khach-hang.index', compact('khachHang', 'search'));
    }

    public function create()
    {
        return view('khach-hang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenTK' => 'nullable|string|max:100|unique:tbl_KhachHang',
            'TenKH' => 'required|string|max:50',
            'GioiTinh' => 'required|boolean',
            'SDT' => 'required|string|max:10',
            'MatKhau' => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
            'Email' => 'nullable|email|unique:tbl_KhachHang',
        ]);
        if (!empty($validated['MatKhau'])) {
            $validated['MatKhau'] = Hash::make($validated['MatKhau']);
        } else {
            $validated['MatKhau'] = Hash::make('123456'); // Mật khẩu mặc định nếu không nhập
        }
        if (empty($validated['TenTK'])) {
            $validated['TenTK'] = 'user' . time(); // Tạo tên tài khoản mặc định nếu không nhập
        }

        KhachHang::create($validated);
        return redirect()->route('khach-hang.index')->with('success', 'Khách hàng đã được thêm.');
    }

    public function show($id)
    {
        $khachHang = KhachHang::findOrFail($id);
        return view('khach-hang.show', compact('khachHang'));
    }

    public function edit($id)
    {
        $khachHang = KhachHang::findOrFail($id);
        return view('khach-hang.edit', compact('khachHang'));
    }

    public function update(Request $request, $id)
    {
        $khachHang = KhachHang::findOrFail($id);
        $validated = $request->validate([
            'TenTK' => 'nullable|string|max:100|unique:tbl_KhachHang,TenTK,' . $id . ',MaKH',
            'TenKH' => 'required|string|max:50',
            'GioiTinh' => 'required|boolean',
            'SDT' => 'required|string|max:10',
            'MatKhau' => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
            'Email' => 'nullable|email|unique:tbl_KhachHang,Email,' . $id . ',MaKH',
        ]);
        if (!empty($validated['MatKhau'])) {
            $validated['MatKhau'] = Hash::make($validated['MatKhau']);
        } else {
            unset($validated['MatKhau']);
        }
        if (empty($validated['TenTK'])) {
            unset($validated['TenTK']);
        }

        $khachHang->update($validated);
        return redirect()->route('khach-hang.index')->with('success', 'Khách hàng đã được cập nhật.');
    }

    public function destroy($id)
    {
        $khachHang = KhachHang::findOrFail($id);
        $khachHang->delete();
        return redirect()->route('khach-hang.index')->with('success', 'Khách hàng đã bị xóa.');
    }
}
