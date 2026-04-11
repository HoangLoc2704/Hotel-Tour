<?php

namespace App\Http\Controllers;

use App\Models\AnhDichVu;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class AnhDichVuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $anhDichVus = AnhDichVu::query()
            ->with('dichVu')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('HinhAnh', 'like', "%{$search}%")
                        ->orWhereHas('dichVu', function ($relationQuery) use ($search) {
                            $relationQuery->where('TenDV', 'like', "%{$search}%")
                                ->orWhere('MaDV', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('MaADV')
            ->paginate(10)
            ->withQueryString();

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($anhDichVus, 'Danh sách ảnh dịch vụ');
        }

        return view('anh-dich-vu.index', compact('anhDichVus', 'search'));
    }

    public function create()
    {
        $dichVus = DichVu::query()
            ->orderBy('TenDV')
            ->get(['MaDV', 'TenDV']);

        return view('anh-dich-vu.create', compact('dichVus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaDV' => 'required|exists:tbl_DichVu,MaDV',
            'image_file' => 'required_without:HinhAnh|nullable|image|max:' . $this->imageUploadMaxKb(),
            'HinhAnh' => 'nullable|string|max:255',
        ], $this->imageUploadValidationMessages());

        $dichVu = DichVu::findOrFail($validated['MaDV']);

        if ($request->hasFile('image_file')) {
            $validated['HinhAnh'] = $this->storeUploadedImage($request->file('image_file'), $dichVu);
        }

        unset($validated['image_file']);

        $anhDichVu = AnhDichVu::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhDichVu, 'Ảnh dịch vụ đã được thêm.', 201);
        }

        return redirect()->route('anh-dich-vu.index')->with('success', 'Ảnh dịch vụ đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $anhDichVu = AnhDichVu::with('dichVu')->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhDichVu, 'Chi tiết ảnh dịch vụ');
        }

        return view('anh-dich-vu.show', compact('anhDichVu'));
    }

    public function edit($id)
    {
        $anhDichVu = AnhDichVu::findOrFail($id);
        $dichVus = DichVu::query()
            ->orderBy('TenDV')
            ->get(['MaDV', 'TenDV']);

        return view('anh-dich-vu.edit', compact('anhDichVu', 'dichVus'));
    }

    public function update(Request $request, $id)
    {
        $anhDichVu = AnhDichVu::findOrFail($id);
        $validated = $request->validate([
            'MaDV' => 'required|exists:tbl_DichVu,MaDV',
            'image_file' => 'nullable|image|max:' . $this->imageUploadMaxKb(),
            'HinhAnh' => 'nullable|string|max:255',
        ], $this->imageUploadValidationMessages());

        $dichVu = DichVu::findOrFail($validated['MaDV']);

        if ($request->hasFile('image_file')) {
            $validated['HinhAnh'] = $this->storeUploadedImage($request->file('image_file'), $dichVu);
        } else {
            $validated['HinhAnh'] = $validated['HinhAnh'] ?: $anhDichVu->HinhAnh;
        }

        unset($validated['image_file']);

        $anhDichVu->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhDichVu, 'Ảnh dịch vụ đã được cập nhật.');
        }

        return redirect()->route('anh-dich-vu.index')->with('success', 'Ảnh dịch vụ đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $anhDichVu = AnhDichVu::findOrFail($id);
        $anhDichVu->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Ảnh dịch vụ đã bị xóa.');
        }

        return redirect()->route('anh-dich-vu.index')->with('success', 'Ảnh dịch vụ đã bị xóa.');
    }

    private function storeUploadedImage(UploadedFile $file, DichVu $dichVu): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'DichVu_' . (int) $dichVu->MaDV . '_' . now()->format('YmdHis') . random_int(100, 999) . '.' . $extension;
        $folder = 'DichVu' . (int) $dichVu->MaDV;
        $destinationDirectory = public_path('img/Service/' . $folder);

        File::ensureDirectoryExists($destinationDirectory);
        $file->move($destinationDirectory, $fileName);

        return $fileName;
    }
}
