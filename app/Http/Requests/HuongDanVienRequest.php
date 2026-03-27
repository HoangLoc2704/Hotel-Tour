<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HuongDanVienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenHDV'    => 'required|string|max:50',
            'NgaySinh'  => 'nullable|date',
            'DiaChi'    => 'required|string|max:255',
            'SDT'       => 'required|string|max:10',
            'TrangThai' => 'required|boolean',
        ];
    }
}
