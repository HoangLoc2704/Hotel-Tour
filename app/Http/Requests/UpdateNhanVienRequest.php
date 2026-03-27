<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNhanVienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('nhan_vien');

        return [
            'TenNV'     => 'required|string|max:50',
            'GioiTinh'  => 'required|boolean',
            'NgaySinh'  => 'required|date',
            'DiaChi'    => 'required|string|max:255',
            'SDT'       => 'required|string|max:10',
            'TenTK'     => 'required|string|max:100|unique:tbl_NhanVien,TenTK,' . $id . ',MaNV',
            'MatKhau'   => 'nullable|string|min:6',
            'Email'     => 'required|email|unique:tbl_NhanVien,Email,' . $id . ',MaNV',
            'TrangThai' => 'required|boolean',
            'MaCV'      => 'required|exists:tbl_ChucVu,MaCV',
        ];
    }
}
