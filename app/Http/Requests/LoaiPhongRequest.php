<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoaiPhongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenLoai'      => 'required|string|max:50',
            'GiaPhong'     => 'nullable|numeric|min:0',
            'SoLuongNguoi' => 'nullable|integer|min:1',
            'HinhAnh'      => 'nullable|string|max:255',
            'image_file'   => 'nullable|image|max:4096',
            'MoTa'         => 'nullable|string|max:255',
        ];
    }
}
