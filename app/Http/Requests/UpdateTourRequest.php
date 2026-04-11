<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenTour'             => 'required|string|max:100',
            'GiaTourNguoiLon'     => 'required|numeric',
            'GiaTourTreEm'        => 'required|numeric',
            'ThoiLuong'           => 'required|integer',
            'DiaDiemKhoiHanh'     => 'nullable|string|max:255',
            'SoLuongKhachToiDa'   => 'nullable|integer',
            'HinhAnh'             => 'nullable|string|max:255',
            'image_file'          => 'nullable|image|max:' . $this->imageUploadMaxKb(),
            'MoTa'                => 'nullable|string|max:255',
            'LichTrinh'           => 'nullable|string|max:255',
            'TrangThai'           => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image_file.image' => 'Vui lòng chọn đúng tệp hình ảnh.',
            'image_file.max' => 'Ảnh tải lên không được vượt quá ' . $this->imageUploadLimitLabel() . '.',
        ];
    }

    private function imageUploadMaxKb(): int
    {
        return max((int) config('uploads.image_max_kb', 900), 1);
    }

    private function imageUploadLimitLabel(): string
    {
        $maxKb = $this->imageUploadMaxKb();

        if ($maxKb >= 1024) {
            $maxMb = $maxKb / 1024;
            $formatted = rtrim(rtrim(number_format($maxMb, 2, '.', ''), '0'), '.');

            return $formatted . ' MB';
        }

        return $maxKb . ' KB';
    }
}
