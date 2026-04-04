<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    protected $table = 'tbl_Phong';
    protected $primaryKey = 'MaPhong';
    public $timestamps = false;

    protected $fillable = [
        'TenPhong',
        'MaLoai',
    ];

    protected $casts = [
        'MaPhong' => 'integer',
        'MaLoai' => 'integer',
    ];

    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'MaLoai', 'MaLoai');
    }

    public function hdPhongs()
    {
        return $this->hasMany(HDPhong::class, 'MaPhong', 'MaPhong');
    }

    public function getGiaPhongAttribute()
    {
        return $this->attributes['GiaPhong'] ?? $this->loaiPhong?->GiaPhong;
    }

    public function getSoLuongNguoiAttribute()
    {
        return $this->attributes['SoLuongNguoi'] ?? $this->loaiPhong?->SoLuongNguoi;
    }

    public function getHinhAnhAttribute()
    {
        return $this->attributes['HinhAnh'] ?? $this->loaiPhong?->HinhAnh;
    }

    public function getMoTaAttribute()
    {
        return $this->attributes['MoTa'] ?? $this->loaiPhong?->MoTa;
    }

    public function roomImagePath(?string $imageName = null): string
    {
        if ($this->loaiPhong) {
            return $this->loaiPhong->roomImagePath($imageName ?: $this->HinhAnh);
        }

        $imageName = $imageName ?: ($this->HinhAnh ?: 'Don1.jpg');

        return 'img/Room/' . $imageName;
    }

    public function getRoomImagePathAttribute(): string
    {
        return $this->roomImagePath();
    }
}
