<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHDTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaHD'        => 'required|exists:tbl_HoaDon,MaHD',
            'MaLKH'       => 'required|exists:tbl_LichKhoiHanh,MaLKH',
            'SoNguoiLon'  => 'nullable|integer',
            'SoTreEm'     => 'nullable|integer',
            'TongTien'    => 'nullable|numeric',
            'TrangThai'   => 'required|boolean',
            'ThanhToan'   => 'required|boolean',
        ];
    }
}
