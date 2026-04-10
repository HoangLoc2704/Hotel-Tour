<?php

namespace App\Http\Controllers;

use App\Http\Requests\LichKhoiHanhRequest;
use App\Models\HuongDanVien;
use App\Models\LichKhoiHanh;
use App\Models\Tour;
use Illuminate\Http\Request;

class LichKhoiHanhController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = LichKhoiHanh::query()
            ->select(['MaLKH', 'MaTour', 'NgayKhoiHanh', 'NgayKetThuc', 'SoChoConLai', 'MaHDV'])
            ->with([
                'tour:MaTour,TenTour',
                'huongDanVien:MaHDV,TenHDV',
            ]);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('tour', function ($tourQuery) use ($search) {
                    $tourQuery->where('TenTour', 'like', "%{$search}%");
                })->orWhere('MaLKH', 'like', "%{$search}%");
            });
        }
        $lichKhoiHanh = $query->paginate(10);

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('lich-khoi-hanh.partials.list', compact('lichKhoiHanh'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($lichKhoiHanh, 'Danh sách lịch khởi hành');
        }

        return view('lich-khoi-hanh.index', compact('lichKhoiHanh', 'search'));
    }

    public function create()
    {
        $tours = Tour::query()
            ->select(['MaTour', 'TenTour'])
            ->orderBy('TenTour')
            ->get();

        $huongDanViens = HuongDanVien::query()
            ->select(['MaHDV', 'TenHDV'])
            ->orderBy('TenHDV')
            ->get();

        return view('lich-khoi-hanh.create', compact('tours', 'huongDanViens'));
    }

    public function store(LichKhoiHanhRequest $request)
    {
        $validated = $request->validated();

        $lichKhoiHanh = LichKhoiHanh::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($lichKhoiHanh, 'Lịch khởi hành đã được thêm.', 201);
        }

        return redirect()->route('lich-khoi-hanh.index')->with('success', 'Lịch khởi hành đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $lichKhoiHanh = LichKhoiHanh::with(['tour', 'huongDanVien'])->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($lichKhoiHanh, 'Chi tiết lịch khởi hành');
        }

        return view('lich-khoi-hanh.show', compact('lichKhoiHanh'));
    }

    public function edit($id)
    {
        $lichKhoiHanh = LichKhoiHanh::findOrFail($id);

        $tours = Tour::query()
            ->select(['MaTour', 'TenTour'])
            ->orderBy('TenTour')
            ->get();

        $huongDanViens = HuongDanVien::query()
            ->select(['MaHDV', 'TenHDV'])
            ->orderBy('TenHDV')
            ->get();

        return view('lich-khoi-hanh.edit', compact('lichKhoiHanh', 'tours', 'huongDanViens'));
    }

    public function update(LichKhoiHanhRequest $request, $id)
    {
        $lichKhoiHanh = LichKhoiHanh::findOrFail($id);
        $validated = $request->validated();

        $lichKhoiHanh->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($lichKhoiHanh, 'Lịch khởi hành đã được cập nhật.');
        }

        return redirect()->route('lich-khoi-hanh.index')->with('success', 'Lịch khởi hành đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $lichKhoiHanh = LichKhoiHanh::findOrFail($id);
        $lichKhoiHanh->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Lịch khởi hành đã bị xóa.');
        }

        return redirect()->route('lich-khoi-hanh.index')->with('success', 'Lịch khởi hành đã bị xóa.');
    }
}
