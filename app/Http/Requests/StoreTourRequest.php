<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaTour'              => 'required|string|max:20|unique:tbl_TOUR',
            'TenTour'             => 'required|string|max:100',
            'GiaTourNguoiLon'     => 'required|numeric',
            'GiaTourTreEm'        => 'required|numeric',
            'ThoiLuong'           => 'required|integer',
            'DiaDiemKhoiHanh'     => 'nullable|string|max:255',
            'SoLuongKhachToiDa'   => 'nullable|integer',
            'HinhAnh'             => 'nullable|string|max:255',
            'MoTa'                => 'nullable|string|max:255',
            'LichTrinh'           => 'nullable|string|max:255',
            'TrangThai'           => 'required|boolean',
        ];
    }
}
