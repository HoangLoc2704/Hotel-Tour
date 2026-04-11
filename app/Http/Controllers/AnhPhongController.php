<?php

namespace App\Http\Controllers;

use App\Models\AnhPhong;
use App\Models\LoaiPhong;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AnhPhongController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $anhPhongs = AnhPhong::query()
            ->with('loaiPhong')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('HinhAnh', 'like', "%{$search}%")
                        ->orWhereHas('loaiPhong', function ($relationQuery) use ($search) {
                            $relationQuery->where('TenLoai', 'like', "%{$search}%")
                                ->orWhere('MaLoai', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('MaAP')
            ->paginate(10)
            ->withQueryString();

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($anhPhongs, 'Danh sách ảnh phòng');
        }

        return view('anh-phong.index', compact('anhPhongs', 'search'));
    }

    public function create()
    {
        $loaiPhongs = LoaiPhong::query()
            ->orderBy('TenLoai')
            ->get(['MaLoai', 'TenLoai']);

        return view('anh-phong.create', compact('loaiPhongs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaLoai' => 'required|exists:tbl_LoaiPhong,MaLoai',
            'image_file' => 'required_without:HinhAnh|nullable|image|max:' . $this->imageUploadMaxKb(),
            'HinhAnh' => 'nullable|string|max:255',
        ], $this->imageUploadValidationMessages());

        $loaiPhong = LoaiPhong::findOrFail($validated['MaLoai']);

        if ($request->hasFile('image_file')) {
            $validated['HinhAnh'] = $this->storeUploadedImage($request->file('image_file'), $loaiPhong);
        }

        unset($validated['image_file']);

        $anhPhong = AnhPhong::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhPhong, 'Ảnh phòng đã được thêm.', 201);
        }

        return redirect()->route('anh-phong.index')->with('success', 'Ảnh phòng đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $anhPhong = AnhPhong::with('loaiPhong')->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhPhong, 'Chi tiết ảnh phòng');
        }

        return view('anh-phong.show', compact('anhPhong'));
    }

    public function edit($id)
    {
        $anhPhong = AnhPhong::findOrFail($id);
        $loaiPhongs = LoaiPhong::query()
            ->orderBy('TenLoai')
            ->get(['MaLoai', 'TenLoai']);

        return view('anh-phong.edit', compact('anhPhong', 'loaiPhongs'));
    }

    public function update(Request $request, $id)
    {
        $anhPhong = AnhPhong::findOrFail($id);
        $validated = $request->validate([
            'MaLoai' => 'required|exists:tbl_LoaiPhong,MaLoai',
            'image_file' => 'nullable|image|max:' . $this->imageUploadMaxKb(),
            'HinhAnh' => 'nullable|string|max:255',
        ], $this->imageUploadValidationMessages());

        $loaiPhong = LoaiPhong::findOrFail($validated['MaLoai']);

        if ($request->hasFile('image_file')) {
            $validated['HinhAnh'] = $this->storeUploadedImage($request->file('image_file'), $loaiPhong);
        } else {
            $validated['HinhAnh'] = $validated['HinhAnh'] ?: $anhPhong->HinhAnh;
        }

        unset($validated['image_file']);

        $anhPhong->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhPhong, 'Ảnh phòng đã được cập nhật.');
        }

        return redirect()->route('anh-phong.index')->with('success', 'Ảnh phòng đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $anhPhong = AnhPhong::findOrFail($id);
        $anhPhong->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Ảnh phòng đã bị xóa.');
        }

        return redirect()->route('anh-phong.index')->with('success', 'Ảnh phòng đã bị xóa.');
    }

    private function storeUploadedImage(UploadedFile $file, LoaiPhong $loaiPhong): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'room_' . now()->format('YmdHis') . random_int(100, 999) . '.' . $extension;
        $relativePath = $loaiPhong->roomImagePath($fileName);
        $relativeDirectory = trim(str_replace('\\', '/', dirname($relativePath)), './');
        $destinationDirectory = public_path($relativeDirectory);

        File::ensureDirectoryExists($destinationDirectory);
        $file->move($destinationDirectory, $fileName);

        return $fileName;
    }
}
