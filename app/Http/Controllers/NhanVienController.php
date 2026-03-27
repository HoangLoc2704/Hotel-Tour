<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNhanVienRequest;
use App\Http\Requests\UpdateNhanVienRequest;
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

    public function store(StoreNhanVienRequest $request)
    {
        $validated = $request->validated();

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

    public function update(UpdateNhanVienRequest $request, $id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        
        $validated = $request->validated();

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
