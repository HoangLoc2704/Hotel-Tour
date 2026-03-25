<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    protected $table = 'tbl_Phong';
    protected $primaryKey = 'MaPhong';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = ['MaPhong', 'TenPhong', 'SoLuongNguoi', 'GiaPhong', 'HinhAnh', 'MoTa', 'MaLoai'];
    
    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'MaLoai', 'MaLoai');
    }

    public function hdPhongs()
    {
        return $this->hasMany(HDPhong::class, 'MaPhong', 'MaPhong');
    }

    public function getMotaAttribute()
    {
        return $this->attributes['MoTa'] ?? null;
    }

    public function setMotaAttribute($value)
    {
        $this->attributes['MoTa'] = $value;
    }
}
