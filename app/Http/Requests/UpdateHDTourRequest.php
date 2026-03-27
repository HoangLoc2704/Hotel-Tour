<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHDTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'SoNguoiLon'  => 'nullable|integer',
            'SoTreEm'     => 'nullable|integer',
            'TongTien'    => 'nullable|numeric',
            'TrangThai'   => 'required|boolean',
        ];
    }
}
