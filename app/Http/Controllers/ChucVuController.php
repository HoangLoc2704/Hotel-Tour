<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChucVuController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->input('search');
        $chucVu = ChucVu::query();
        if ($search) {
            $chucVu->where('TenCV', 'like', "%{$search}%");
        }
        $chucVu = $chucVu->paginate(10);

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('chuc-vu.partials.list', compact('chucVu'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($chucVu, 'Danh sách chức vụ');
        }

        return view('chuc-vu.index', compact('chucVu', 'search'));
    }

    public function create(): View
    {
        return view('chuc-vu.create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'TenCV' => 'required|string|max:20',
        ]);

        $chucVu = ChucVu::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($chucVu, 'Thêm chức vụ thành công', 201);
        }

        return redirect()->route('chuc-vu.index')->with('success', 'Thêm chức vụ thành công');
    }

    public function show(Request $request, $id): View|JsonResponse
    {
        $chucVu = ChucVu::findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($chucVu, 'Chi tiết chức vụ');
        }

        return view('chuc-vu.show', compact('chucVu'));
    }

    public function edit($id): View
    {
        $chucVu = ChucVu::findOrFail($id);
        return view('chuc-vu.edit', compact('chucVu'));
    }

    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $chucVu = ChucVu::findOrFail($id);
        $validated = $request->validate([
            'TenCV' => 'required|string|max:20',
        ]);
        $chucVu->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($chucVu, 'Cập nhật chức vụ thành công');
        }

        return redirect()->route('chuc-vu.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $chucVu = ChucVu::findOrFail($id);
        $chucVu->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Xóa chức vụ thành công');
        }

        return redirect()->route('chuc-vu.index')->with('success', 'Xóa thành công');
    }
}
