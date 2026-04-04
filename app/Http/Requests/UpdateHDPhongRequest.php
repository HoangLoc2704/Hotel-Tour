<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHDPhongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'NgayNhanPhong' => 'nullable|date',
            'NgayTraPhong'  => 'nullable|date|after_or_equal:NgayNhanPhong',
            'TongTien'      => 'nullable|numeric|min:0',
            'TrangThai'     => 'required|boolean',
            'ThanhToan'     => 'required|boolean',
        ];
    }
}
