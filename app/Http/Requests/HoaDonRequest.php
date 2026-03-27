<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HoaDonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaKH'      => 'required|exists:tbl_KhachHang,MaKH',
            'NgayTao'   => 'nullable|date',
            'ThanhTien' => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
        ];
    }
}
