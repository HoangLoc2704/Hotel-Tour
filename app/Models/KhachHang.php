<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    protected $table = 'tbl_KhachHang';
    protected $primaryKey = 'MaKH';
    public $timestamps = false;
    
    protected $fillable = ['TenKH', 'GioiTinh', 'SDT', 'MatKhau', 'TrangThai', 'Email'];
    
    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaKH', 'MaKH');
    }
}
