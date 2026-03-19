<?php

namespace App\Http\Controllers;

use App\Models\HDDichVu;
use Illuminate\Http\Request;

class HDDichVuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = HDDichVu::with(['hoaDon.khachHang', 'dichVu']);
        if ($search) {
            $query->where('MaHD', 'like', "%{$search}%")
                  ->orWhere('MaDV', 'like', "%{$search}%");
        }
        $hdDichVu = $query->paginate(10);
        return view('hd-dich-vu.index', compact('hdDichVu', 'search'));
    }

    public function create()
    {
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $dichVus = \App\Models\DichVu::all();
        return view('hd-dich-vu.create', compact('hoaDons', 'dichVus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaHD' => 'required|exists:tbl_HoaDon,MaHD',
            'MaDV' => 'required|exists:tbl_DichVu,MaDV',
            'SoLuong' => 'nullable|integer',
            'TongTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        HDDichVu::create($validated);
        return redirect()->route('hd-dich-vu.index')->with('success', 'Hóa đơn dịch vụ đã được thêm.');
    }

    public function show($maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->with(['hoaDon.khachHang', 'dichVu'])->firstOrFail();
        return view('hd-dich-vu.show', compact('hdDichVu'));
    }

    public function edit($maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->firstOrFail();
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $dichVus = \App\Models\DichVu::all();
        return view('hd-dich-vu.edit', compact('hdDichVu', 'hoaDons', 'dichVus'));
    }

    public function update(Request $request, $maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->firstOrFail();
        $validated = $request->validate([
            'SoLuong' => 'nullable|integer',
            'TongTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);
        $hdDichVu->update($validated);
        return redirect()->route('hd-dich-vu.index')->with('success', 'Hóa đơn dịch vụ đã được cập nhật.');
    }

    public function destroy($maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->firstOrFail();
        $hdDichVu->delete();
        return redirect()->route('hd-dich-vu.index')->with('success', 'Hóa đơn dịch vụ đã bị xóa.');
    }
}
