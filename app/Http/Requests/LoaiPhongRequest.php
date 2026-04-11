<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoaiPhongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenLoai'      => 'required|string|max:50',
            'GiaPhong'     => 'nullable|numeric|min:0',
            'SoLuongNguoi' => 'nullable|integer|min:1',
            'HinhAnh'      => 'nullable|string|max:255',
            'image_file'   => 'nullable|image|max:' . $this->imageUploadMaxKb(),
            'MoTa'         => 'nullable|string|max:255',
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
