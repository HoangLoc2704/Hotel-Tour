<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKhachHangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('khach_hang');

        return [
            'TenKH'     => 'required|string|max:50',
            'GioiTinh'  => 'required|boolean',
            'SDT'       => 'required|string|max:10',
            'MatKhau'   => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
            'Email'     => 'nullable|email|unique:tbl_KhachHang,Email,' . $id . ',MaKH',
        ];
    }
}
