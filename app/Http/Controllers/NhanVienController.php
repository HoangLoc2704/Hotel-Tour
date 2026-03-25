<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\ChucVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $nhanVien = NhanVien::with('chucVu');
        
        if ($search) {
            $nhanVien->where('TenNV', 'like', '%' . $search . '%')
                     ->orWhere('Email', 'like', '%' . $search . '%')
                     ->orWhere('SDT', 'like', '%' . $search . '%');
        }
        
        $nhanVien = $nhanVien->paginate(10);

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('nhan-vien.partials.list', compact('nhanVien'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($nhanVien, 'Danh sách nhân viên');
        }
        
        return view('nhan-vien.index', compact('nhanVien', 'search'));
    }

    public function create()
    {
        $chucVu = ChucVu::all();
        return view('nhan-vien.create', compact('chucVu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenNV' => 'required|string|max:50',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date',
            'DiaChi' => 'required|string|max:255',
            'SDT' => 'required|string|max:10',
            'TenTK' => 'required|string|max:100|unique:tbl_NhanVien',
            'MatKhau' => 'required|string|min:6',
            'Email' => 'required|email|unique:tbl_NhanVien',
            'TrangThai' => 'required|boolean',
            'MaCV' => 'required|exists:tbl_ChucVu,MaCV',
        ]);

        $validated['MatKhau'] = Hash::make($validated['MatKhau']);
        $nhanVien = NhanVien::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($nhanVien, 'Thêm nhân viên thành công!', 201);
        }

        return redirect()->route('nhan-vien.index')->with('success', 'Thêm nhân viên thành công!');
    }

    public function show(Request $request, $id)
    {
        $nhanVien = NhanVien::with('chucVu')->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($nhanVien, 'Chi tiết nhân viên');
        }

        return view('nhan-vien.show', compact('nhanVien'));
    }

    public function edit($id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        $chucVu = ChucVu::all();
        return view('nhan-vien.edit', compact('nhanVien', 'chucVu'));
    }

    public function update(Request $request, $id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        
        $validated = $request->validate([
            'TenNV' => 'required|string|max:50',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date',
            'DiaChi' => 'required|string|max:255',
            'SDT' => 'required|string|max:10',
            'TenTK' => 'required|string|max:100|unique:tbl_NhanVien,TenTK,' . $id . ',MaNV',
            'MatKhau' => 'nullable|string|min:6',
            'Email' => 'required|email|unique:tbl_NhanVien,Email,' . $id . ',MaNV',
            'TrangThai' => 'required|boolean',
            'MaCV' => 'required|exists:tbl_ChucVu,MaCV',
        ]);

        if (!empty($validated['MatKhau'])) {
            $validated['MatKhau'] = Hash::make($validated['MatKhau']);
        } else {
            unset($validated['MatKhau']);
        }

        $nhanVien->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($nhanVien, 'Cập nhật nhân viên thành công!');
        }

        return redirect()->route('nhan-vien.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function destroy(Request $request, $id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        $nhanVien->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Xóa nhân viên thành công!');
        }

        return redirect()->route('nhan-vien.index')->with('success', 'Xóa nhân viên thành công!');
    }
}
