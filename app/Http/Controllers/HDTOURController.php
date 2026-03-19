<?php

namespace App\Http\Controllers;

use App\Models\HDTOUR;
use Illuminate\Http\Request;

class HDTOURController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = HDTOUR::with(['hoaDon.khachHang', 'lichKhoiHanh.tour']);
        if ($search) {
            $query->where('MaHD', 'like', "%{$search}%")
                  ->orWhere('MaLKH', 'like', "%{$search}%");
        }
        $hdTour = $query->paginate(10);
        return view('hd-tour.index', compact('hdTour', 'search'));
    }

    public function create()
    {
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $lichKhoiHanhs = \App\Models\LichKhoiHanh::with('tour')->get();
        return view('hd-tour.create', compact('hoaDons', 'lichKhoiHanhs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaHD' => 'required|exists:tbl_HoaDon,MaHD',
            'MaLKH' => 'required|exists:tbl_LichKhoiHanh,MaLKH',
            'SoNguoiLon' => 'nullable|integer',
            'SoTreEm' => 'nullable|integer',
            'TongTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        HDTOUR::create($validated);
        return redirect()->route('hd-tour.index')->with('success', 'Hóa đơn tour đã được thêm.');
    }

    public function show($maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->with(['hoaDon.khachHang', 'lichKhoiHanh.tour'])->firstOrFail();
        return view('hd-tour.show', compact('hdTour'));
    }

    public function edit($maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->firstOrFail();
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $lichKhoiHanhs = \App\Models\LichKhoiHanh::with('tour')->get();
        return view('hd-tour.edit', compact('hdTour', 'hoaDons', 'lichKhoiHanhs'));
    }

    public function update(Request $request, $maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->firstOrFail();
        $validated = $request->validate([
            'SoNguoiLon' => 'nullable|integer',
            'SoTreEm' => 'nullable|integer',
            'TongTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);
        $hdTour->update($validated);
        return redirect()->route('hd-tour.index')->with('success', 'Hóa đơn tour đã được cập nhật.');
    }

    public function destroy($maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->firstOrFail();
        $hdTour->delete();
        return redirect()->route('hd-tour.index')->with('success', 'Hóa đơn tour đã bị xóa.');
    }
}
