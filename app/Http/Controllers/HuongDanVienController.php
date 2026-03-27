<?php

namespace App\Http\Controllers;

use App\Http\Requests\HuongDanVienRequest;
use App\Models\HuongDanVien;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class HuongDanVienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = HuongDanVien::query();

        if ($search) {
            $query->where('TenHDV', 'like', "%{$search}%")
                ->orWhere('SDT', 'like', "%{$search}%")
                ->orWhere('DiaChi', 'like', "%{$search}%");
        }

        $huongDanVien = $query->paginate(10);

        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('huong-dan-vien.partials.list', compact('huongDanVien'));
        }

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($huongDanVien, 'Danh sách hướng dẫn viên');
        }

        return view('huong-dan-vien.index', compact('huongDanVien', 'search'));
    }

    public function create()
    {
        return view('huong-dan-vien.create');
    }

    public function store(HuongDanVienRequest $request)
    {
        $validated = $request->validated();

        $huongDanVien = HuongDanVien::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($huongDanVien, 'Thêm hướng dẫn viên thành công', 201);
        }

        return redirect()->route('huong-dan-vien.index')->with('success', 'Thêm hướng dẫn viên thành công');
    }

    public function show(Request $request, $id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($huongDanVien, 'Chi tiết hướng dẫn viên');
        }

        return view('huong-dan-vien.show', compact('huongDanVien'));
    }

    public function edit($id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);
        return view('huong-dan-vien.edit', compact('huongDanVien'));
    }

    public function update(HuongDanVienRequest $request, $id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);
        $validated = $request->validated();

        $huongDanVien->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($huongDanVien, 'Cập nhật hướng dẫn viên thành công');
        }

        return redirect()->route('huong-dan-vien.index')->with('success', 'Cập nhật hướng dẫn viên thành công');
    }

    public function destroy(Request $request, $id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);
        try {
            $huongDanVien->delete();

            if ($this->wantsJson($request)) {
                return $this->jsonSuccess(null, 'Xóa hướng dẫn viên thành công');
            }

            return redirect()->route('huong-dan-vien.index')->with('success', 'Xóa hướng dẫn viên thành công');
        } catch (QueryException $e) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa vì hướng dẫn viên đang được sử dụng.',
                ], 409);
            }

            return redirect()->route('huong-dan-vien.index')->with('error', 'Không thể xóa vì hướng dẫn viên đang được sử dụng.');
        }
    }
}
