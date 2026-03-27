<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHDDichVuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'SoLuong'   => 'nullable|integer',
            'TongTien'  => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ];
    }
}
