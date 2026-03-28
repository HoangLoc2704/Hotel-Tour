<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHDDichVuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaHD'      => 'required|exists:tbl_HoaDon,MaHD',
            'MaDV'      => 'required|exists:tbl_DichVu,MaDV',
            'SoLuong'   => 'nullable|integer',
            'TongTien'  => 'nullable|numeric',
            'TrangThai' => 'required|boolean',
            'ThanhToan' => 'required|boolean',
        ];
    }
}
