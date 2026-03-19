<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $table = 'tbl_TOUR';
    protected $primaryKey = 'MaTour';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = ['MaTour', 'TenTour', 'GiaTourNguoiLon', 'GiaTourTreEm', 'ThoiLuong', 'DiaDiemKhoiHanh', 'SoLuongKhachToiDa', 'HinhAnh', 'MoTa', 'LichTrinh', 'TrangThai'];
    
    public function lichKhoiHanh()
    {
        return $this->hasMany(LichKhoiHanh::class, 'MaTour', 'MaTour');
    }
}
