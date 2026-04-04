<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Tour extends Model
{
    protected $table = 'tbl_TOUR';
    protected $primaryKey = 'MaTour';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['MaTour', 'TenTour', 'GiaTourNguoiLon', 'GiaTourTreEm', 'ThoiLuong', 'DiaDiemKhoiHanh', 'SoLuongKhachToiDa', 'HinhAnh', 'MoTa', 'LichTrinh', 'TrangThai'];

    protected $casts = [
        'GiaTourNguoiLon' => 'float',
        'GiaTourTreEm' => 'float',
        'ThoiLuong' => 'integer',
        'SoLuongKhachToiDa' => 'integer',
        'TrangThai' => 'boolean',
    ];

    public function lichKhoiHanh()
    {
        return $this->hasMany(LichKhoiHanh::class, 'MaTour', 'MaTour');
    }

    public function anhTours()
    {
        return $this->hasMany(AnhTour::class, 'MaTour', 'MaTour');
    }

    public function getGalleryImagesAttribute(): Collection
    {
        $images = $this->relationLoaded('anhTours')
            ? $this->anhTours->pluck('HinhAnh')
            : collect();

        if ($images->isEmpty() && !empty($this->HinhAnh)) {
            $images = collect([$this->HinhAnh]);
        }

        return $images
            ->map(fn ($image) => $this->resolveTourImageName($image))
            ->filter(fn ($image) => !empty($image))
            ->unique()
            ->values();
    }

    public function tourImagePath(?string $imageName = null): string
    {
        $imageName = $this->resolveTourImageName($imageName ?: ($this->HinhAnh ?: 'TourNuiCam1.jpg'));
        $folder = $this->resolveTourImageFolder($imageName);

        return $folder !== ''
            ? 'img/Tour/' . $folder . '/' . $imageName
            : 'img/Tour/' . $imageName;
    }

    public function getTourImagePathAttribute(): string
    {
        return $this->tourImagePath();
    }

    private function resolveTourImageFolder(?string $imageName = null): string
    {
        $imageName = $this->resolveTourImageName($imageName ?: $this->HinhAnh ?: '');
        $fileName = pathinfo($imageName, PATHINFO_FILENAME);

        if ($fileName === '') {
            return '';
        }

        return preg_match('/^(.*?)(?:_?\d+)$/', $fileName, $matches) === 1
            ? $matches[1]
            : $fileName;
    }

    private function resolveTourImageName(?string $imageName): string
    {
        $imageName = $this->normalizeTourImageName($imageName);

        if ($imageName === '' || preg_match('/^tour\d+\.(jpe?g|png|gif|webp)$/i', $imageName) === 1) {
            $fallbackFromCode = match ((string) $this->MaTour) {
                'T001' => 'TourNuiCam1.jpg',
                'T002' => 'Tour30_4_1.jpg',
                default => '',
            };

            $fallbackFromGallery = $this->relationLoaded('anhTours')
                ? $this->anhTours->pluck('HinhAnh')->filter()->first()
                : null;

            return $this->normalizeTourImageName($fallbackFromGallery ?: $fallbackFromCode ?: 'TourNuiCam1.jpg');
        }

        return $imageName;
    }

    private function normalizeTourImageName(?string $imageName): string
    {
        $imageName = trim((string) $imageName);

        if (
            $imageName !== ''
            && !Str::contains($imageName, '.')
            && preg_match('/(jpe?g|png|gif|webp)$/i', $imageName, $matches) === 1
        ) {
            $extension = $matches[1];
            $baseName = substr($imageName, 0, -strlen($extension));

            return $baseName . '.' . $extension;
        }

        return $imageName;
    }
}
