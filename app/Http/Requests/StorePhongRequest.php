<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaPhong'  => 'nullable|string|max:10',
            'TenPhong' => 'required|string|max:10|unique:tbl_Phong,TenPhong',
            'MaLoai'   => 'required|exists:tbl_LoaiPhong,MaLoai',
        ];
    }
}
