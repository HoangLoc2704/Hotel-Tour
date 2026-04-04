<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('phong');

        return [
            'TenPhong' => 'required|string|max:10|unique:tbl_Phong,TenPhong,' . $id . ',MaPhong',
            'MaLoai'   => 'required|exists:tbl_LoaiPhong,MaLoai',
        ];
    }
}
