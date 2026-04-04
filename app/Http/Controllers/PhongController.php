<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhongRequest;
use App\Http\Requests\UpdatePhongRequest;
use App\Models\Phong;
use Carbon\CarbonPeriod;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhongController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $phong = Phong::query()
            ->select(['MaPhong', 'TenPhong', 'SoLuongNguoi', 'GiaPhong', 'HinhAnh', 'MoTa', 'MaLoai'])
            ->with([
            'loaiPhong:MaLoai,TenLoai',
            'hdPhongs' => function ($query) {
                $query->select(['MaPhong', 'NgayNhanPhong', 'NgayTraPhong'])
                    ->whereNotNull('NgayNhanPhong')
                    ->whereNotNull('NgayTraPhong')
                    ->orderBy('NgayNhanPhong', 'desc');
            }
        ]);
        if ($search) {
            $phong->where(function ($query) use ($search) {
                $query->where('TenPhong', 'like', "%{$search}%")
                    ->orWhereHas('loaiPhong', function ($relation) use ($search) {
                        $relation->where('TenLoai', 'like', "%{$search}%");
                    });
            });
        }
        $phong = $phong->paginate(10);

        $phong->getCollection()->transform(function ($room) {
            $bookedDateMap = [];

            foreach ($room->hdPhongs as $booking) {
                if (!$booking->NgayNhanPhong || !$booking->NgayTraPhong) {
                    continue;
                }

                $period = CarbonPeriod::create($booking->NgayNhanPhong, $booking->NgayTraPhong);
                foreach ($period as $date) {
                    $bookedDateMap[$date->format('Y-m-d')] = true;
                }
            }

            $room->bookedDates = array_keys($bookedDateMap);
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
        $loaiPhong = \App\Models\LoaiPhong::select('MaLoai', 'TenLoai')->get();
        return view('phong.create', compact('loaiPhong'));
    }

    public function store(StorePhongRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('HinhAnhFile')) {
            $file = $request->file('HinhAnhFile');
            $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $fileName);
            $validated['HinhAnh'] = $fileName;
        }

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
        $loaiPhong = \App\Models\LoaiPhong::select('MaLoai', 'TenLoai')->get();
        return view('phong.edit', compact('phong', 'loaiPhong'));
    }

    public function update(UpdatePhongRequest $request, $id)
    {
        $phong = Phong::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('HinhAnhFile')) {
            $file = $request->file('HinhAnhFile');
            $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $fileName);
            $validated['HinhAnh'] = $fileName;
        } else {
            unset($validated['HinhAnh']);
        }

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
