<?php

namespace App\Http\Controllers;

use App\Models\HuongDanVien;
use Illuminate\Http\Request;

class HuongDanVienController extends Controller
{
    public function index()
    {
        $huongDanVien = HuongDanVien::all();
        return response()->json($huongDanVien);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenHDV' => 'required|string|max:50',
            'NgaySinh' => 'date',
            'DiaChi' => 'required|string|max:255',
            'SDT' => 'required|string|max:10',
            'TrangThai' => 'boolean',
        ]);

        $huongDanVien = HuongDanVien::create($validated);
        return response()->json($huongDanVien, 201);
    }

    public function show($id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);
        return response()->json($huongDanVien);
    }

    public function update(Request $request, $id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);
        $validated = $request->validate([
            'TenHDV' => 'string|max:50',
            'NgaySinh' => 'date',
            'DiaChi' => 'string|max:255',
            'SDT' => 'string|max:10',
            'TrangThai' => 'boolean',
        ]);
        $huongDanVien->update($validated);
        return response()->json($huongDanVien);
    }

    public function destroy($id)
    {
        $huongDanVien = HuongDanVien::findOrFail($id);
        $huongDanVien->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
