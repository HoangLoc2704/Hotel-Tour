<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHDPhongRequest;
use App\Http\Requests\UpdateHDPhongRequest;
use App\Models\HDPhong;
use App\Models\HoaDon;
use App\Models\Phong;
use Carbon\Carbon;
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

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('hd-phong.partials.list', compact('hdPhong'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($hdPhong, 'Danh sách hóa đơn phòng');
        }

        return view('hd-phong.index', compact('hdPhong', 'search'));
    }

    public function create()
    {
        $hoaDons = \App\Models\HoaDon::query()
            ->select(['MaHD', 'MaKH'])
            ->with('khachHang:MaKH,TenKH')
            ->orderByDesc('MaHD')
            ->get();
        $phongs = \App\Models\Phong::query()
            ->select(['MaPhong', 'TenPhong'])
            ->orderBy('TenPhong')
            ->get();
        return view('hd-phong.create', compact('hoaDons', 'phongs'));
    }

    public function store(StoreHDPhongRequest $request)
    {
        $validated = $request->validated();

        if (!empty($validated['NgayNhanPhong']) && !empty($validated['NgayTraPhong'])) {
            $isConflict = HDPhong::where('MaPhong', $validated['MaPhong'])
                ->where(function ($query) use ($validated) {
                    $query->whereDate('NgayNhanPhong', '<=', $validated['NgayTraPhong'])
                        ->whereDate('NgayTraPhong', '>=', $validated['NgayNhanPhong']);
                })
                ->exists();

            if ($isConflict) {
                if ($this->wantsJson($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Phòng này đã trùng lịch đặt trong khoảng ngày bạn chọn.',
                    ], 422);
                }

                return back()
                    ->withInput()
                    ->withErrors(['NgayNhanPhong' => 'Phòng này đã trùng lịch đặt trong khoảng ngày bạn chọn.']);
            }
        }

        $phong = Phong::query()->findOrFail($validated['MaPhong']);
        $giaPhong = (float) ($phong->GiaPhong ?? 0);
        $soDem = 1;
        if (!empty($validated['NgayNhanPhong']) && !empty($validated['NgayTraPhong'])) {
            $nhan = Carbon::parse($validated['NgayNhanPhong']);
            $tra = Carbon::parse($validated['NgayTraPhong']);
            $soDem = max(1, $nhan->diffInDays($tra));
        }
        $validated['TongTien'] = $giaPhong * $soDem;

        $hdPhong = HDPhong::create($validated);
        HoaDon::recalculateThanhTien($validated['MaHD']);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdPhong, 'Hóa đơn phòng đã được thêm.', 201);
        }

        return redirect()->route('hd-phong.index')->with('success', 'Hóa đơn phòng đã được thêm.');
    }

    public function show(Request $request, $maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->with(['hoaDon.khachHang', 'phong'])->firstOrFail();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdPhong, 'Chi tiết hóa đơn phòng');
        }

        return view('hd-phong.show', compact('hdPhong'));
    }

    public function edit($maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->firstOrFail();
        $hoaDons = \App\Models\HoaDon::query()
            ->select(['MaHD', 'MaKH'])
            ->with('khachHang:MaKH,TenKH')
            ->orderByDesc('MaHD')
            ->get();
        $phongs = \App\Models\Phong::query()
            ->select(['MaPhong', 'TenPhong'])
            ->orderBy('TenPhong')
            ->get();
        return view('hd-phong.edit', compact('hdPhong', 'hoaDons', 'phongs'));
    }

    public function update(UpdateHDPhongRequest $request, $maHD, $maPhong)
    {
        $hdPhong = HDPhong::where('MaHD', $maHD)->where('MaPhong', $maPhong)->firstOrFail();
        $validated = $request->validated();

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
                if ($this->wantsJson($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Phòng này đã trùng lịch đặt trong khoảng ngày bạn chọn.',
                    ], 422);
                }

                return back()
                    ->withInput()
                    ->withErrors(['NgayNhanPhong' => 'Phòng này đã trùng lịch đặt trong khoảng ngày bạn chọn.']);
            }
        }

        $maPhongMoi = $hdPhong->MaPhong;
        $ngayNhan = $validated['NgayNhanPhong'] ?? $hdPhong->NgayNhanPhong;
        $ngayTra = $validated['NgayTraPhong'] ?? $hdPhong->NgayTraPhong;

        $phong = Phong::query()->findOrFail($maPhongMoi);
        $giaPhong = (float) ($phong->GiaPhong ?? 0);
        $soDem = 1;
        if (!empty($ngayNhan) && !empty($ngayTra)) {
            $nhan = Carbon::parse($ngayNhan);
            $tra = Carbon::parse($ngayTra);
            $soDem = max(1, $nhan->diffInDays($tra));
        }
        $validated['TongTien'] = $giaPhong * $soDem;

        HDPhong::query()
            ->where('MaHD', $maHD)
            ->where('MaPhong', $maPhong)
            ->update($validated);

        $hdPhong = HDPhong::where('MaHD', $maHD)
            ->where('MaPhong', $maPhong)
            ->firstOrFail();

        HoaDon::recalculateThanhTien($maHD);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdPhong, 'Hóa đơn phòng đã được cập nhật.');
        }

        return redirect()->route('hd-phong.index')->with('success', 'Hóa đơn phòng đã được cập nhật.');
    }

    public function destroy(Request $request, $maHD, $maPhong)
    {
        HDPhong::query()
            ->where('MaHD', $maHD)
            ->where('MaPhong', $maPhong)
            ->delete();

        HoaDon::recalculateThanhTien($maHD);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Hóa đơn phòng đã bị xóa.');
        }

        return redirect()->route('hd-phong.index')->with('success', 'Hóa đơn phòng đã bị xóa.');
    }
}
