<?php

namespace App\Http\Controllers;

use App\Models\AnhTour;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class AnhTourController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $anhTours = AnhTour::query()
            ->with('tour')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('HinhAnh', 'like', "%{$search}%")
                        ->orWhereHas('tour', function ($relationQuery) use ($search) {
                            $relationQuery->where('TenTour', 'like', "%{$search}%")
                                ->orWhere('MaTour', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('MaAT')
            ->paginate(10)
            ->withQueryString();

        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($anhTours, 'Danh sách ảnh tour');
        }

        return view('anh-tour.index', compact('anhTours', 'search'));
    }

    public function create()
    {
        $tours = Tour::query()
            ->orderBy('TenTour')
            ->get(['MaTour', 'TenTour']);

        return view('anh-tour.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaTour' => 'required|exists:tbl_TOUR,MaTour',
            'image_file' => 'required_without:HinhAnh|nullable|image|max:4096',
            'HinhAnh' => 'nullable|string|max:255',
        ]);

        $tour = Tour::findOrFail($validated['MaTour']);

        if ($request->hasFile('image_file')) {
            $validated['HinhAnh'] = $this->storeUploadedImage($request->file('image_file'), $tour);
        }

        unset($validated['image_file']);

        $anhTour = AnhTour::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhTour, 'Ảnh tour đã được thêm.', 201);
        }

        return redirect()->route('anh-tour.index')->with('success', 'Ảnh tour đã được thêm.');
    }

    public function show(Request $request, $id)
    {
        $anhTour = AnhTour::with('tour')->findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhTour, 'Chi tiết ảnh tour');
        }

        return view('anh-tour.show', compact('anhTour'));
    }

    public function edit($id)
    {
        $anhTour = AnhTour::findOrFail($id);
        $tours = Tour::query()
            ->orderBy('TenTour')
            ->get(['MaTour', 'TenTour']);

        return view('anh-tour.edit', compact('anhTour', 'tours'));
    }

    public function update(Request $request, $id)
    {
        $anhTour = AnhTour::findOrFail($id);
        $validated = $request->validate([
            'MaTour' => 'required|exists:tbl_TOUR,MaTour',
            'image_file' => 'nullable|image|max:4096',
            'HinhAnh' => 'nullable|string|max:255',
        ]);

        $tour = Tour::findOrFail($validated['MaTour']);

        if ($request->hasFile('image_file')) {
            $validated['HinhAnh'] = $this->storeUploadedImage($request->file('image_file'), $tour);
        } else {
            $validated['HinhAnh'] = $validated['HinhAnh'] ?: $anhTour->HinhAnh;
        }

        unset($validated['image_file']);

        $anhTour->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($anhTour, 'Ảnh tour đã được cập nhật.');
        }

        return redirect()->route('anh-tour.index')->with('success', 'Ảnh tour đã được cập nhật.');
    }

    public function destroy(Request $request, $id)
    {
        $anhTour = AnhTour::findOrFail($id);
        $anhTour->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Ảnh tour đã bị xóa.');
        }

        return redirect()->route('anh-tour.index')->with('success', 'Ảnh tour đã bị xóa.');
    }

    private function storeUploadedImage(UploadedFile $file, Tour $tour): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $folder = trim(str_replace('img/Tour/', '', str_replace('\\', '/', dirname($tour->tourImagePath()))), '/');
        $prefix = $folder !== '' ? $folder : 'Tour';
        $fileName = $prefix . '_' . now()->format('YmdHis') . random_int(100, 999) . '.' . $extension;
        $destinationDirectory = public_path('img/Tour' . ($folder !== '' ? '/' . $folder : ''));

        File::ensureDirectoryExists($destinationDirectory);
        $file->move($destinationDirectory, $fileName);

        return $fileName;
    }
}
