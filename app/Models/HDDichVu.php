<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HDDichVu extends Model
{
    protected $table = 'tbl_HDDichVu';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['MaHD', 'MaDV', 'SoLuong', 'TongTien', 'TrangThai', 'ThanhToan'];

    protected $casts = [
        'MaHD' => 'integer',
        'MaDV' => 'integer',
        'SoLuong' => 'integer',
        'TongTien' => 'float',
        'TrangThai' => 'boolean',
        'ThanhToan' => 'boolean',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD');
    }

    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'MaDV', 'MaDV');
    }
}
