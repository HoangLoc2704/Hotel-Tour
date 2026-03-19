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

        HoaDon::create($validated);
        return redirect()->route('hoa-don.index')->with('success', 'Hóa đơn đã được thêm.');
    }

    public function show($id)
    {
        $hoaDon = HoaDon::with('khachHang')->findOrFail($id);
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
        return redirect()->route('hoa-don.index')->with('success', 'Hóa đơn đã được cập nhật.');
    }

    public function destroy($id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        $hoaDon->delete();
        return redirect()->route('hoa-don.index')->with('success', 'Hóa đơn đã bị xóa.');
    }
}
