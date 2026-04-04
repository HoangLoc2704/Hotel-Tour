<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DichVu extends Model
{
    protected $table = 'tbl_DichVu';
    protected $primaryKey = 'MaDV';
    public $timestamps = false;

    protected $fillable = ['TenDV', 'GiaDV', 'TrangThai'];

    protected $casts = [
        'MaDV' => 'integer',
        'GiaDV' => 'float',
        'TrangThai' => 'boolean',
    ];

    public function hdDichVus()
    {
        return $this->hasMany(HDDichVu::class, 'MaDV', 'MaDV');
    }

    public function anhDichVus()
    {
        return $this->hasMany(AnhDichVu::class, 'MaDV', 'MaDV');
    }

    public function getGalleryImagesAttribute(): Collection
    {
        $images = $this->relationLoaded('anhDichVus')
            ? $this->anhDichVus->pluck('HinhAnh')
            : collect();

        if ($images->isEmpty()) {
            $images = collect(['DichVu_' . (int) ($this->MaDV ?: 1) . '_1.jpg']);
        }

        return $images
            ->map(fn ($image) => $this->normalizeServiceImageName($image))
            ->filter(fn ($image) => !empty($image))
            ->unique()
            ->values();
    }

    public function serviceImagePath(?string $imageName = null): string
    {
        $defaultImage = 'DichVu_' . (int) ($this->MaDV ?: 1) . '_1.jpg';
        $imageName = $this->normalizeServiceImageName($imageName ?: $defaultImage);
        $folder = $this->resolveServiceImageFolder($imageName);

        return $folder !== ''
            ? 'img/Service/' . $folder . '/' . $imageName
            : 'img/Service/' . $imageName;
    }

    public function getServiceImagePathAttribute(): string
    {
        return $this->serviceImagePath();
    }

    private function resolveServiceImageFolder(?string $imageName = null): string
    {
        $defaultImage = 'DichVu_' . (int) ($this->MaDV ?: 1) . '_1.jpg';
        $imageName = $this->normalizeServiceImageName($imageName ?: $defaultImage);

        if (preg_match('/^DichVu_(\d+)_/i', pathinfo($imageName, PATHINFO_BASENAME), $matches) === 1) {
            return 'DichVu' . $matches[1];
        }

        return !empty($this->MaDV) ? 'DichVu' . $this->MaDV : '';
    }

    private function normalizeServiceImageName(?string $imageName): string
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
