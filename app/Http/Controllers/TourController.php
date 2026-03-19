<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Tour::query();
        if ($search) {
            $query->where('TenTour', 'like', "%{$search}%");
        }
        $tours = $query->paginate(10);
        return view('tour.index', compact('tours', 'search'));
    }

    public function create()
    {
        return view('tour.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaTour' => 'required|string|max:20|unique:tbl_TOUR',
            'TenTour' => 'required|string|max:100',
            'GiaTourNguoiLon' => 'required|numeric',
            'GiaTourTreEm' => 'required|numeric',
            'ThoiLuong' => 'required|integer',
            'DiaDiemKhoiHanh' => 'nullable|string|max:255',
            'SoLuongKhachToiDa' => 'nullable|integer',
            'HinhAnh' => 'nullable|string|max:255',
            'MoTa' => 'nullable|string|max:255',
            'LichTrinh' => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
        ]);

        Tour::create($validated);
        return redirect()->route('tour.index')->with('success', 'Thêm tour thành công');
    }

    public function show($id)
    {
        $tour = Tour::findOrFail($id);
        return view('tour.show', compact('tour'));
    }

    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        return view('tour.edit', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $validated = $request->validate([
            'TenTour' => 'required|string|max:100',
            'GiaTourNguoiLon' => 'required|numeric',
            'GiaTourTreEm' => 'required|numeric',
            'ThoiLuong' => 'required|integer',
            'DiaDiemKhoiHanh' => 'nullable|string|max:255',
            'SoLuongKhachToiDa' => 'nullable|integer',
            'HinhAnh' => 'nullable|string|max:255',
            'MoTa' => 'nullable|string|max:255',
            'LichTrinh' => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
        ]);
        $tour->update($validated);
        return redirect()->route('tour.index')->with('success', 'Cập nhật tour thành công');
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return redirect()->route('tour.index')->with('success', 'Xóa tour thành công');
    }
}
