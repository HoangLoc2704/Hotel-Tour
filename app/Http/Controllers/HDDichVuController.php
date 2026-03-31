<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHDDichVuRequest;
use App\Http\Requests\UpdateHDDichVuRequest;
use App\Models\DichVu;
use App\Models\HDDichVu;
use App\Models\HoaDon;
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

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('hd-dich-vu.partials.list', compact('hdDichVu'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($hdDichVu, 'Danh sách hóa đơn dịch vụ');
        }

        return view('hd-dich-vu.index', compact('hdDichVu', 'search'));
    }

    public function create()
    {
        $hoaDons = \App\Models\HoaDon::query()
            ->select(['MaHD', 'MaKH'])
            ->with('khachHang:MaKH,TenKH')
            ->orderByDesc('MaHD')
            ->get();
        $dichVus = \App\Models\DichVu::query()
            ->select(['MaDV', 'TenDV'])
            ->orderBy('TenDV')
            ->get();
        return view('hd-dich-vu.create', compact('hoaDons', 'dichVus'));
    }

    public function store(StoreHDDichVuRequest $request)
    {
        $validated = $request->validated();

        $dichVu = DichVu::query()->findOrFail($validated['MaDV']);
        $soLuong = (int) ($validated['SoLuong'] ?? 1);
        $validated['TongTien'] = (float) ($dichVu->GiaDV ?? 0) * max(1, $soLuong);

        $hdDichVu = HDDichVu::create($validated);
        HoaDon::recalculateThanhTien($validated['MaHD']);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdDichVu, 'Hóa đơn dịch vụ đã được thêm.', 201);
        }

        return redirect()->route('hd-dich-vu.index')->with('success', 'Hóa đơn dịch vụ đã được thêm.');
    }

    public function show(Request $request, $maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->with(['hoaDon.khachHang', 'dichVu'])->firstOrFail();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdDichVu, 'Chi tiết hóa đơn dịch vụ');
        }

        return view('hd-dich-vu.show', compact('hdDichVu'));
    }

    public function edit($maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->firstOrFail();
        $hoaDons = \App\Models\HoaDon::query()
            ->select(['MaHD', 'MaKH'])
            ->with('khachHang:MaKH,TenKH')
            ->orderByDesc('MaHD')
            ->get();
        $dichVus = \App\Models\DichVu::query()
            ->select(['MaDV', 'TenDV'])
            ->orderBy('TenDV')
            ->get();
        return view('hd-dich-vu.edit', compact('hdDichVu', 'hoaDons', 'dichVus'));
    }

    public function update(UpdateHDDichVuRequest $request, $maHD, $maDV)
    {
        $hdDichVu = HDDichVu::where('MaHD', $maHD)->where('MaDV', $maDV)->firstOrFail();
        $validated = $request->validated();

        $dichVu = DichVu::query()->findOrFail($hdDichVu->MaDV);
        $soLuong = (int) ($validated['SoLuong'] ?? $hdDichVu->SoLuong ?? 1);
        $validated['TongTien'] = (float) ($dichVu->GiaDV ?? 0) * max(1, $soLuong);

        HDDichVu::query()
            ->where('MaHD', $maHD)
            ->where('MaDV', $maDV)
            ->update($validated);

        $hdDichVu = HDDichVu::where('MaHD', $maHD)
            ->where('MaDV', $maDV)
            ->firstOrFail();

        HoaDon::recalculateThanhTien($maHD);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($hdDichVu, 'Hóa đơn dịch vụ đã được cập nhật.');
        }

        return redirect()->route('hd-dich-vu.index')->with('success', 'Hóa đơn dịch vụ đã được cập nhật.');
    }

    public function destroy(Request $request, $maHD, $maDV)
    {
        HDDichVu::query()
            ->where('MaHD', $maHD)
            ->where('MaDV', $maDV)
            ->delete();

        HoaDon::recalculateThanhTien($maHD);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Hóa đơn dịch vụ đã bị xóa.');
        }

        return redirect()->route('hd-dich-vu.index')->with('success', 'Hóa đơn dịch vụ đã bị xóa.');
    }
}
