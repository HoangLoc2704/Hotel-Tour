<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHDTourRequest;
use App\Http\Requests\UpdateHDTourRequest;
use App\Models\HDTOUR;
use App\Models\HoaDon;
use App\Models\LichKhoiHanh;
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
        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('hd-tour.partials.list', compact('hdTour'));
        }
        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($hdTour, 'Danh sách hóa đơn tour');
        }

        return view('hd-tour.index', compact('hdTour', 'search'));
    }

    public function create()
    {
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $lichKhoiHanhs = \App\Models\LichKhoiHanh::with('tour')->get();
        return view('hd-tour.create', compact('hoaDons', 'lichKhoiHanhs'));
    }

    public function store(StoreHDTourRequest $request)
    {
        $validated = $request->validated();

        $lich = LichKhoiHanh::query()->with('tour')->findOrFail($validated['MaLKH']);
        $soNguoiLon = (int) ($validated['SoNguoiLon'] ?? 0);
        $soTreEm = (int) ($validated['SoTreEm'] ?? 0);
        $giaNguoiLon = (float) ($lich->tour->GiaTourNguoiLon ?? 0);
        $giaTreEm = (float) ($lich->tour->GiaTourTreEm ?? 0);
        $validated['TongTien'] = ($soNguoiLon * $giaNguoiLon) + ($soTreEm * $giaTreEm);

        $hdTour = HDTOUR::create($validated);
        HoaDon::recalculateThanhTien($validated['MaHD']);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdTour, 'Hóa đơn tour đã được thêm.', 201);
        }

        return redirect()->route('hd-tour.index')->with('success', 'Hóa đơn tour đã được thêm.');
    }

    public function show(Request $request, $maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->with(['hoaDon.khachHang', 'lichKhoiHanh.tour'])->firstOrFail();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdTour, 'Chi tiết hóa đơn tour');
        }

        return view('hd-tour.show', compact('hdTour'));
    }

    public function edit($maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->firstOrFail();
        $hoaDons = \App\Models\HoaDon::with('khachHang')->get();
        $lichKhoiHanhs = \App\Models\LichKhoiHanh::with('tour')->get();
        return view('hd-tour.edit', compact('hdTour', 'hoaDons', 'lichKhoiHanhs'));
    }

    public function update(UpdateHDTourRequest $request, $maHD, $maLKH)
    {
        $hdTour = HDTOUR::where('MaHD', $maHD)->where('MaLKH', $maLKH)->firstOrFail();
        $validated = $request->validated();

        $lich = LichKhoiHanh::query()->with('tour')->findOrFail($hdTour->MaLKH);
        $soNguoiLon = (int) ($validated['SoNguoiLon'] ?? $hdTour->SoNguoiLon ?? 0);
        $soTreEm = (int) ($validated['SoTreEm'] ?? $hdTour->SoTreEm ?? 0);
        $giaNguoiLon = (float) ($lich->tour->GiaTourNguoiLon ?? 0);
        $giaTreEm = (float) ($lich->tour->GiaTourTreEm ?? 0);
        $validated['TongTien'] = ($soNguoiLon * $giaNguoiLon) + ($soTreEm * $giaTreEm);

        HDTOUR::query()
            ->where('MaHD', $maHD)
            ->where('MaLKH', $maLKH)
            ->update($validated);

        $hdTour = HDTOUR::where('MaHD', $maHD)
            ->where('MaLKH', $maLKH)
            ->firstOrFail();

        HoaDon::recalculateThanhTien($maHD);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdTour, 'Hóa đơn tour đã được cập nhật.');
        }

        return redirect()->route('hd-tour.index')->with('success', 'Hóa đơn tour đã được cập nhật.');
    }

    public function destroy(Request $request, $maHD, $maLKH)
    {
        HDTOUR::query()
            ->where('MaHD', $maHD)
            ->where('MaLKH', $maLKH)
            ->delete();

        HoaDon::recalculateThanhTien($maHD);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Hóa đơn tour đã bị xóa.');
        }

        return redirect()->route('hd-tour.index')->with('success', 'Hóa đơn tour đã bị xóa.');
    }
}
