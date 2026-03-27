<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LichKhoiHanhRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaTour'        => 'required|exists:tbl_TOUR,MaTour',
            'NgayKhoiHanh'  => 'required|date',
            'NgayKetThuc'   => 'nullable|date|after_or_equal:NgayKhoiHanh',
            'SoChoConLai'   => 'nullable|integer',
            'MaHDV'         => 'required|exists:tbl_HuongDanVien,MaHDV',
            'TaiXe'         => 'nullable|string|max:100',
            'PhuongTien'    => 'nullable|string|max:100',
        ];
    }
}
