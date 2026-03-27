<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKhachHangRequest;
use App\Http\Requests\UpdateKhachHangRequest;
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

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('khach-hang.partials.list', compact('khachHang'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($khachHang, 'Danh sách khách hàng');
        }

        return view('khach-hang.index', compact('khachHang', 'search'));
    }

    public function create()
    {
        return view('khach-hang.create');
    }

    public function store(StoreKhachHangRequest $request)
    {
        $validated = $request->validated();
        if (!empty($validated['MatKhau'])) {
            $validated['MatKhau'] = Hash::make($validated['MatKhau']);
        } else {
            $validated['MatKhau'] = Hash::make('123456'); // Mật khẩu mặc định nếu không nhập
        }

        $khachHang = KhachHang::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($khachHang, 'Khách hàng đã được thêm.', 201);
        }

        return redirect()->route('khach-hang.index')->with('success', 'Khách hàng đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $khachHang = KhachHang::findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($khachHang, 'Chi tiết khách hàng');
        }

        return view('khach-hang.show', compact('khachHang'));
    }

    public function edit($id)
    {
        $khachHang = KhachHang::findOrFail($id);
        return view('khach-hang.edit', compact('khachHang'));
    }

    public function update(UpdateKhachHangRequest $request, $id)
    {
        $khachHang = KhachHang::findOrFail($id);
        $validated = $request->validated();
        if (!empty($validated['MatKhau'])) {
            $validated['MatKhau'] = Hash::make($validated['MatKhau']);
        } else {
            unset($validated['MatKhau']);
        }

        $khachHang->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($khachHang, 'Khách hàng đã được cập nhật.');
        }

        return redirect()->route('khach-hang.index')->with('success', 'Khách hàng đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $khachHang = KhachHang::findOrFail($id);
        $khachHang->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Khách hàng đã bị xóa.');
        }

        return redirect()->route('khach-hang.index')->with('success', 'Khách hàng đã bị xóa.');
    }
}
