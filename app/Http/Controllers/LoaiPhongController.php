<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoaiPhongRequest;
use App\Models\LoaiPhong;
use Illuminate\Http\Request;

class LoaiPhongController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = LoaiPhong::query();
        if ($search) {
            $query->where('TenLoai', 'like', "%{$search}%");
        }
        $loaiPhong = $query->paginate(10);

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($loaiPhong, 'Danh sách loại phòng');
        }

        return view('loai-phong.index', compact('loaiPhong', 'search'));
    }

    public function create()
    {
        return view('loai-phong.create');
    }

    public function store(LoaiPhongRequest $request)
    {
        $validated = $request->validated();

        $loaiPhong = LoaiPhong::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($loaiPhong, 'Loại phòng đã được thêm.', 201);
        }

        return redirect()->route('loai-phong.index')->with('success', 'Loại phòng đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($loaiPhong, 'Chi tiết loại phòng');
        }

        return view('loai-phong.show', compact('loaiPhong'));
    }

    public function edit($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('loai-phong.edit', compact('loaiPhong'));
    }

    public function update(LoaiPhongRequest $request, $id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        $validated = $request->validated();
        $loaiPhong->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($loaiPhong, 'Loại phòng đã được cập nhật.');
        }

        return redirect()->route('loai-phong.index')->with('success', 'Loại phòng đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        $loaiPhong->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Loại phòng đã bị xóa.');
        }

        return redirect()->route('loai-phong.index')->with('success', 'Loại phòng đã bị xóa.');
    }
}
