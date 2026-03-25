<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use Illuminate\Http\Request;

class DichVuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = DichVu::query();
        if ($search) {
            $query->where('TenDV', 'like', "%{$search}%");
        }
        $dichVu = $query->paginate(10);
        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('dich-vu.partials.list', compact('dichVu'));
        }
        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($dichVu, 'Danh sách dịch vụ');
        }

        return view('dich-vu.index', compact('dichVu', 'search'));
    }

    public function create()
    {
        return view('dich-vu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenDV' => 'required|string|max:50',
            'GiaDV' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);

        $dichVu = DichVu::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($dichVu, 'Dịch vụ đã được thêm.', 201);
        }

        return redirect()->route('dich-vu.index')->with('success', 'Dịch vụ đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($dichVu, 'Chi tiết dịch vụ');
        }

        return view('dich-vu.show', compact('dichVu'));
    }

    public function edit($id)
    {
        $dichVu = DichVu::findOrFail($id);
        return view('dich-vu.edit', compact('dichVu'));
    }

    public function update(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);
        $validated = $request->validate([
            'TenDV' => 'required|string|max:50',
            'GiaDV' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ]);
        $dichVu->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($dichVu, 'Dịch vụ đã được cập nhật.');
        }

        return redirect()->route('dich-vu.index')->with('success', 'Dịch vụ đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);
        $dichVu->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Dịch vụ đã bị xóa.');
        }

        return redirect()->route('dich-vu.index')->with('success', 'Dịch vụ đã bị xóa.');
    }
}
