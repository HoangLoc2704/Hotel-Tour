<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    protected $table = 'tbl_Phong';
    protected $primaryKey = 'MaPhong';
    public $timestamps = false;
    
    protected $fillable = ['TenPhong', 'SoLuongNguoi', 'GiaPhong', 'TrangThai', 'HinhAnh', 'Mota', 'MaLoai'];
    
    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'MaLoai', 'MaLoai');
    }

    public function hdPhongs()
    {
        return $this->hasMany(HDPhong::class, 'MaPhong', 'MaPhong');
    }
}
