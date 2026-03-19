<?php

namespace App\Http\Controllers;

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
        return view('loai-phong.index', compact('loaiPhong', 'search'));
    }

    public function create()
    {
        return view('loai-phong.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenLoai' => 'required|string|max:50',
        ]);

        LoaiPhong::create($validated);
        return redirect()->route('loai-phong.index')->with('success', 'Loại phòng đã được thêm.');
    }

    public function show($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('loai-phong.show', compact('loaiPhong'));
    }

    public function edit($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('loai-phong.edit', compact('loaiPhong'));
    }

    public function update(Request $request, $id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        $validated = $request->validate([
            'TenLoai' => 'required|string|max:50',
        ]);
        $loaiPhong->update($validated);
        return redirect()->route('loai-phong.index')->with('success', 'Loại phòng đã được cập nhật.');
    }

    public function destroy($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        $loaiPhong->delete();
        return redirect()->route('loai-phong.index')->with('success', 'Loại phòng đã bị xóa.');
    }
}
