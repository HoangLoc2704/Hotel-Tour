<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = HoaDon::with('khachHang');
        if ($search) {
            $query->whereHas('khachHang', function($q) use ($search) {
                $q->where('TenKH', 'like', "%{$search}%");
            })->orWhere('MaHD', 'like', "%{$search}%");
        }
        $hoaDon = $query->paginate(10);
        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('hoa-don.partials.list', compact('hoaDon'));
        }
        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($hoaDon, 'Danh sách hóa đơn');
        }

        return view('hoa-don.index', compact('hoaDon', 'search'));
    }

    public function create()
    {
        $khachHang = \App\Models\KhachHang::all();
        return view('hoa-don.create', compact('khachHang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaKH' => 'required|exists:tbl_KhachHang,MaKH',
            'NgayTao' => 'nullable|date',
            'ThanhTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        if (empty($validated['NgayTao'])) {
            $validated['NgayTao'] = now()->toDateString();
        }

        $hoaDon = HoaDon::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hoaDon, 'Hóa đơn đã được thêm.', 201);
        }

        return redirect()->route('hoa-don.index')->with('success', 'Hóa đơn đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $hoaDon = HoaDon::with('khachHang')->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hoaDon, 'Chi tiết hóa đơn');
        }

        return view('hoa-don.show', compact('hoaDon'));
    }

    public function edit($id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        $khachHang = \App\Models\KhachHang::all();
        return view('hoa-don.edit', compact('hoaDon', 'khachHang'));
    }

    public function update(Request $request, $id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        $validated = $request->validate([
            'MaKH' => 'required|exists:tbl_KhachHang,MaKH',
            'NgayTao' => 'nullable|date',
            'ThanhTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        $hoaDon->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hoaDon, 'Hóa đơn đã được cập nhật.');
        }

        return redirect()->route('hoa-don.index')->with('success', 'Hóa đơn đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        $hoaDon->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Hóa đơn đã bị xóa.');
        }

        return redirect()->route('hoa-don.index')->with('success', 'Hóa đơn đã bị xóa.');
    }
}
