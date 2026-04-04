<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LoaiPhong extends Model
{
    protected $table = 'tbl_LoaiPhong';
    protected $primaryKey = 'MaLoai';
    public $timestamps = false;

    protected $fillable = [
        'TenLoai',
        'GiaPhong',
        'SoLuongNguoi',
        'HinhAnh',
        'MoTa',
    ];

    protected $casts = [
        'MaLoai' => 'integer',
        'GiaPhong' => 'float',
        'SoLuongNguoi' => 'integer',
    ];

    public function phongs()
    {
        return $this->hasMany(Phong::class, 'MaLoai', 'MaLoai');
    }

    public function phong()
    {
        return $this->phongs();
    }

    public function anhPhongs()
    {
        return $this->hasMany(AnhPhong::class, 'MaLoai', 'MaLoai');
    }

    public function getGalleryImagesAttribute(): Collection
    {
        $images = $this->relationLoaded('anhPhongs')
            ? $this->anhPhongs->pluck('HinhAnh')
            : collect();

        if ($images->isEmpty() && !empty($this->HinhAnh)) {
            $images = collect([$this->HinhAnh]);
        }

        return $images
            ->filter(fn ($image) => !empty($image))
            ->unique()
            ->values();
    }

    public function roomImagePath(?string $imageName = null): string
    {
        $imageName = $imageName ?: ($this->HinhAnh ?: 'Don1.jpg');
        $folder = $this->resolveRoomImageFolder($imageName);

        return $folder !== ''
            ? 'img/Room/' . $folder . '/' . $imageName
            : 'img/Room/' . $imageName;
    }

    public function getRoomImagePathAttribute(): string
    {
        return $this->roomImagePath();
    }

    private function resolveRoomImageFolder(?string $imageName = null): string
    {
        $imageName = (string) ($imageName ?: $this->HinhAnh ?: '');

        foreach (['DonView', 'DoiView', 'GDView', 'Don', 'Doi', 'GD'] as $prefix) {
            if (Str::startsWith($imageName, $prefix)) {
                return $prefix;
            }
        }

        $tenLoai = Str::lower((string) $this->TenLoai);

        return match (true) {
            Str::contains($tenLoai, ['đơn view', 'don view']) => 'DonView',
            Str::contains($tenLoai, ['đôi view', 'doi view']) => 'DoiView',
            Str::contains($tenLoai, ['gia đình view', 'gia dinh view', 'gd view']) => 'GDView',
            Str::contains($tenLoai, ['đơn', 'don']) => 'Don',
            Str::contains($tenLoai, ['đôi', 'doi']) => 'Doi',
            Str::contains($tenLoai, ['gia đình', 'gia dinh', 'gd']) => 'GD',
            default => '',
        };
    }
}
