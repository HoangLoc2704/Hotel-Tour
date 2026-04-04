<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HDTOUR extends Model
{
    protected $table = 'tbl_HDTOUR';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['MaHD', 'MaLKH', 'SoNguoiLon', 'SoTreEm', 'TongTien', 'TrangThai', 'ThanhToan'];

    protected $casts = [
        'MaHD' => 'integer',
        'MaLKH' => 'integer',
        'SoNguoiLon' => 'integer',
        'SoTreEm' => 'integer',
        'TongTien' => 'float',
        'TrangThai' => 'boolean',
        'ThanhToan' => 'boolean',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD');
    }

    public function lichKhoiHanh()
    {
        return $this->belongsTo(LichKhoiHanh::class, 'MaLKH', 'MaLKH');
    }
}
