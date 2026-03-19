<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $chucVu = ChucVu::query();
        if ($search) {
            $chucVu->where('TenCV', 'like', "%{$search}%");
        }
        $chucVu = $chucVu->paginate(10);
        return view('chuc-vu.index', compact('chucVu', 'search'));
    }

    public function create()
    {
        return view('chuc-vu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenCV' => 'required|string|max:20',
        ]);

        ChucVu::create($validated);
        return redirect()->route('chuc-vu.index')->with('success', 'Thêm chức vụ thành công');
    }

    public function show($id)
    {
        $chucVu = ChucVu::findOrFail($id);
        return view('chuc-vu.show', compact('chucVu'));
    }

    public function edit($id)
    {
        $chucVu = ChucVu::findOrFail($id);
        return view('chuc-vu.edit', compact('chucVu'));
    }

    public function update(Request $request, $id)
    {
        $chucVu = ChucVu::findOrFail($id);
        $validated = $request->validate([
            'TenCV' => 'required|string|max:20',
        ]);
        $chucVu->update($validated);
        return redirect()->route('chuc-vu.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $chucVu = ChucVu::findOrFail($id);
        $chucVu->delete();
        return redirect()->route('chuc-vu.index')->with('success', 'Xóa thành công');
    }
}
