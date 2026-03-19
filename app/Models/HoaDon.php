<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'tbl_HoaDon';
    protected $primaryKey = 'MaHD';
    public $timestamps = false;
    
    protected $fillable = ['MaKH', 'NgayTao', 'ThanhTien', 'TrangThai'];
    
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }
}
