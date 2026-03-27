<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHDPhongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaHD'          => 'required|exists:tbl_HoaDon,MaHD',
            'MaPhong'       => 'required|exists:tbl_Phong,MaPhong',
            'NgayNhanPhong' => 'nullable|date',
            'NgayTraPhong'  => 'nullable|date|after_or_equal:NgayNhanPhong',
            'TongTien'      => 'nullable|numeric',
            'TrangThai'     => 'required|boolean',
        ];
    }
}
