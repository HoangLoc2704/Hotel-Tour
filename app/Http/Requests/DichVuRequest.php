<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DichVuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenDV'     => 'required|string|max:50',
            'GiaDV'     => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ];
    }
}
