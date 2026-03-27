<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Tour::query();
        if ($search) {
            $query->where('TenTour', 'like', "%{$search}%");
        }
        $tours = $query->paginate(10);
        if ($request->ajax() && !$this->wantsJson($request)) {
            return view('tour.partials.list', compact('tours'));
        }
        if ($this->wantsJson($request)) {
            return $this->jsonPaginated($tours, 'Danh sách tour');
        }

        return view('tour.index', compact('tours', 'search'));
    }

    public function create()
    {
        return view('tour.create');
    }

    public function store(StoreTourRequest $request)
    {
        $validated = $request->validated();

        $tour = Tour::create($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($tour, 'Thêm tour thành công', 201);
        }

        return redirect()->route('tour.index')->with('success', 'Thêm tour thành công');
    }

    public function show(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($tour, 'Chi tiết tour');
        }

        return view('tour.show', compact('tour'));
    }

    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        return view('tour.edit', compact('tour'));
    }

    public function update(UpdateTourRequest $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $validated = $request->validated();
        $tour->update($validated);

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess($tour, 'Cập nhật tour thành công');
        }

        return redirect()->route('tour.index')->with('success', 'Cập nhật tour thành công');
    }

    public function destroy(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();

        if ($this->wantsJson($request)) {
            return $this->jsonSuccess(null, 'Xóa tour thành công');
        }

        return redirect()->route('tour.index')->with('success', 'Xóa tour thành công');
    }
}
