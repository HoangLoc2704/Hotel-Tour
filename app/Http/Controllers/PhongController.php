<?php

namespace App\Http\Controllers;

use App\Models\Phong;
use Carbon\CarbonPeriod;
use Illuminate\Database\QueryException;
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

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('phong.partials.list', compact('phong'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($phong, 'Danh sách phòng');
        }

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
            'MaPhong' => 'required|string|max:10|unique:tbl_Phong,MaPhong',
            'TenPhong' => 'required|string|max:50',
            'SoLuongNguoi' => 'required|integer',
            'GiaPhong' => 'required|numeric',
            'HinhAnh' => 'nullable|string|max:255',
            'MoTa' => 'nullable|string|max:255',
            'MaLoai' => 'required|exists:tbl_LoaiPhong,MaLoai',
        ]);

        $phong = Phong::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($phong, 'Thêm phòng thành công', 201);
        }

        return redirect()->route('phong.index')->with('success', 'Thêm phòng thành công');
    }

    public function show(Request $request, $id)
    {
        $phong = Phong::with('loaiPhong')->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($phong, 'Chi tiết phòng');
        }

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
            'HinhAnh' => 'nullable|string|max:255',
            'MoTa' => 'nullable|string|max:255',
            'MaLoai' => 'required|exists:tbl_LoaiPhong,MaLoai',
        ]);

        $phong->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($phong, 'Cập nhật phòng thành công');
        }

        return redirect()->route('phong.index')->with('success', 'Cập nhật phòng thành công');
    }

    public function destroy(Request $request, $id)
    {
        $phong = Phong::findOrFail($id);
        try {
            $phong->delete();

            if ($this->wantsJson($request)) {
                return $this->jsonSuccess(null, 'Xóa phòng thành công');
            }

            return redirect()->route('phong.index')->with('success', 'Xóa phòng thành công');
        } catch (QueryException $e) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa phòng vì đang có dữ liệu liên quan.',
                ], 409);
            }

            return redirect()->route('phong.index')->with('error', 'Không thể xóa phòng vì đang có dữ liệu liên quan.');
        }
    }
}
