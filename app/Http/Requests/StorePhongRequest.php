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
            'MaPhong'       => 'required|string|max:10|unique:tbl_Phong,MaPhong',
            'TenPhong'      => 'required|string|max:50',
            'SoLuongNguoi'  => 'required|integer',
            'GiaPhong'      => 'required|numeric',
            'HinhAnh'       => 'nullable|string|max:255',
            'MoTa'          => 'nullable|string|max:255',
            'MaLoai'        => 'required|exists:tbl_LoaiPhong,MaLoai',
        ];
    }
}
