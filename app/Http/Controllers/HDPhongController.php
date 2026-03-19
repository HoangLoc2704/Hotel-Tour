<?php

namespace App\Http\Controllers;

use App\Models\HDPhong;
use Illuminate\Http\Request;

class HDPhongController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = HDPhong::with(['hoaDon.khachHang', 'phong']);
        if ($search) {
            $query->where('MaHD', 'like', "%{$search}%")
                  ->orWhere('MaPhong', 'like', "%{$search}%");
        }
        $hdPhong = $query->paginate(10);
        return view('hd-phong.index', compact('hdPhong', 'search'));
    }

    public function create()
    {
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $phongs = \App\Models\Phong::all();
        return view('hd-phong.create', compact('hoaDons', 'phongs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaHD' => 'required|exists:tbl_HoaDon,MaHD',
            'MaPhong' => 'required|exists:tbl_Phong,MaPhong',
            'NgayNhanPhong' => 'nullable|date',
            'NgayTraPhong' => 'nullable|date|after_or_equal:NgayNhanPhong',
            'TongTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        if (!empty($validated['NgayNhanPhong']) && !empty($validated['NgayTraPhong'])) {
            $isConflict = HDPhong::where('MaPhong', $validated['MaPhong'])
                ->where(function ($query) use ($validated) {
                    $query->whereDate('NgayNhanPhong', '<=', $validated['NgayTraPhong'])
                        ->whereDate('NgayTraPhong', '>=', $validated['NgayNhanPhong']);
                })
                ->exists();

            if ($isConflict) {
                return back()
                    ->withInput()
                    ->withErrors(['NgayNhanPhong' => 'Phòng này đã trùng lịch đặt trong khoảng ngày bạn chọn.']);
            }
        }

        HDPhong::create($validated);
        return redirect()->route('hd-phong.index')->with('success', 'Hóa đơn phòng đã được thêm.');
    }

    public function show($maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->with(['hoaDon.khachHang', 'phong'])->firstOrFail();
        return view('hd-phong.show', compact('hdPhong'));
    }

    public function edit($maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->firstOrFail();
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $phongs = \App\Models\Phong::all();
        return view('hd-phong.edit', compact('hdPhong', 'hoaDons', 'phongs'));
    }

    public function update(Request $request, $maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->firstOrFail();
        $validated = $request->validate([
            'NgayNhanPhong' => 'nullable|date',
            'NgayTraPhong' => 'nullable|date|after_or_equal:NgayNhanPhong',
            'TongTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        if (!empty($validated['NgayNhanPhong']) && !empty($validated['NgayTraPhong'])) {
            $isConflict = HDPhong::where('MaPhong', $hdPhong->MaPhong)
                ->where(function ($query) use ($validated) {
                    $query->whereDate('NgayNhanPhong', '<=', $validated['NgayTraPhong'])
                        ->whereDate('NgayTraPhong', '>=', $validated['NgayNhanPhong']);
                })
                ->where(function ($query) use ($maHD, $maPhong) {
                    $query->where('MaHD', '<>', $maHD)
                        ->orWhere('MaPhong', '<>', $maPhong);
                })
                ->exists();

            if ($isConflict) {
                return back()
                    ->withInput()
                    ->withErrors(['NgayNhanPhong' => 'Phòng này đã trùng lịch đặt trong khoảng ngày bạn chọn.']);
            }
        }

        $hdPhong->update($validated);
        return redirect()->route('hd-phong.index')->with('success', 'Hóa đơn phòng đã được cập nhật.');
    }

    public function destroy($maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->firstOrFail();
        $hdPhong->delete();
        return redirect()->route('hd-phong.index')->with('success', 'Hóa đơn phòng đã bị xóa.');
    }
}
