<?php

namespace App\Http\Controllers;

use App\Models\Phong;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class PhongController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $phong = Phong::with([
            'loaiPhong',
            'hdPhongs' => function ($query) {
                $query->whereNotNull('NgayNhanPhong')
                    ->whereNotNull('NgayTraPhong')
                    ->orderBy('NgayNhanPhong', 'desc');
            }
        ]);
        if ($search) {
            $phong->where('TenPhong', 'like', "%{$search}%")
                  ->orWhereHas('loaiPhong', function ($query) use ($search) {
                      $query->where('TenLoai', 'like', "%{$search}%");
                  });
        }
        $phong = $phong->paginate(10);

        $phong->getCollection()->transform(function ($room) {
            $bookedDates = [];

            foreach ($room->hdPhongs as $booking) {
                if (!$booking->NgayNhanPhong || !$booking->NgayTraPhong) {
                    continue;
                }

                $period = CarbonPeriod::create($booking->NgayNhanPhong, $booking->NgayTraPhong);
                foreach ($period as $date) {
                    $bookedDates[] = $date->format('Y-m-d');
                }
            }

            $room->bookedDates = array_values(array_unique($bookedDates));
            return $room;
        });

        return view('phong.index', compact('phong', 'search'));
    }

    public function create()
    {
        $loaiPhong = \App\Models\LoaiPhong::all();
        return view('phong.create', compact('loaiPhong'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenPhong' => 'required|string|max:50',
            'SoLuongNguoi' => 'required|integer',
            'GiaPhong' => 'required|numeric',
            'TrangThai' => 'required|boolean',
            'HinhAnh' => 'nullable|string|max:255',
            'Mota' => 'nullable|string|max:255',
            'MaLoai' => 'required|exists:tbl_LoaiPhong,MaLoai',
        ]);

        Phong::create($validated);
        return redirect()->route('phong.index')->with('success', 'Thêm phòng thành công');
    }

    public function show($id)
    {
        $phong = Phong::with('loaiPhong')->findOrFail($id);
        return view('phong.show', compact('phong'));
    }

    public function edit($id)
    {
        $phong = Phong::findOrFail($id);
        $loaiPhong = \App\Models\LoaiPhong::all();
        return view('phong.edit', compact('phong', 'loaiPhong'));
    }

    public function update(Request $request, $id)
    {
        $phong = Phong::findOrFail($id);
        $validated = $request->validate([
            'TenPhong' => 'required|string|max:50',
            'SoLuongNguoi' => 'required|integer',
            'GiaPhong' => 'required|numeric',
            'TrangThai' => 'required|boolean',
            'HinhAnh' => 'nullable|string|max:255',
            'Mota' => 'nullable|string|max:255',
            'MaLoai' => 'required|exists:tbl_LoaiPhong,MaLoai',
        ]);

        $phong->update($validated);
        return redirect()->route('phong.index')->with('success', 'Cập nhật phòng thành công');
    }

    public function destroy($id)
    {
        $phong = Phong::findOrFail($id);
        $phong->delete();
        return redirect()->route('phong.index')->with('success', 'Xóa phòng thành công');
    }
}
