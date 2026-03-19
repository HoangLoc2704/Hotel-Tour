<?php

namespace App\Http\Controllers;

use App\Models\LichKhoiHanh;
use Illuminate\Http\Request;

class LichKhoiHanhController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = LichKhoiHanh::with(['tour', 'huongDanVien']);
        if ($search) {
            $query->whereHas('tour', function($q) use ($search) {
                $q->where('TenTour', 'like', "%{$search}%");
            })->orWhere('MaLKH', 'like', "%{$search}%");
        }
        $lichKhoiHanh = $query->paginate(10);
        return view('lich-khoi-hanh.index', compact('lichKhoiHanh', 'search'));
    }

    public function create()
    {
        $tours = \App\Models\Tour::all();
        $huongDanViens = \App\Models\HuongDanVien::all();
        return view('lich-khoi-hanh.create', compact('tours', 'huongDanViens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaTour' => 'required|exists:tbl_TOUR,MaTour',
            'NgayKhoiHanh' => 'required|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayKhoiHanh',
            'SoChoConLai' => 'nullable|integer',
            'MaHDV' => 'required|exists:tbl_HuongDanVien,MaHDV',
            'TaiXe' => 'nullable|string|max:100',
            'PhuongTien' => 'nullable|string|max:100',
        ]);

        LichKhoiHanh::create($validated);
        return redirect()->route('lich-khoi-hanh.index')->with('success', 'Lịch khởi hành đã được thêm.');
    }

    public function show($id)
    {
        $lichKhoiHanh = LichKhoiHanh::with(['tour', 'huongDanVien'])->findOrFail($id);
        return view('lich-khoi-hanh.show', compact('lichKhoiHanh'));
    }

    public function edit($id)
    {
        $lichKhoiHanh = LichKhoiHanh::findOrFail($id);
        $tours = \App\Models\Tour::all();
        $huongDanViens = \App\Models\HuongDanVien::all();
        return view('lich-khoi-hanh.edit', compact('lichKhoiHanh', 'tours', 'huongDanViens'));
    }

    public function update(Request $request, $id)
    {
        $lichKhoiHanh = LichKhoiHanh::findOrFail($id);
        $validated = $request->validate([
            'MaTour' => 'required|exists:tbl_TOUR,MaTour',
            'NgayKhoiHanh' => 'required|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayKhoiHanh',
            'SoChoConLai' => 'nullable|integer',
            'MaHDV' => 'required|exists:tbl_HuongDanVien,MaHDV',
            'TaiXe' => 'nullable|string|max:100',
            'PhuongTien' => 'nullable|string|max:100',
        ]);

        $lichKhoiHanh->update($validated);
        return redirect()->route('lich-khoi-hanh.index')->with('success', 'Lịch khởi hành đã được cập nhật.');
    }

    public function destroy($id)
    {
        $lichKhoiHanh = LichKhoiHanh::findOrFail($id);
        $lichKhoiHanh->delete();
        return redirect()->route('lich-khoi-hanh.index')->with('success', 'Lịch khởi hành đã bị xóa.');
    }
}
