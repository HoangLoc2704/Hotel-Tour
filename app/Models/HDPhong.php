<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HDPhong extends Model
{
    protected $table = 'tbl_HDPhong';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['MaHD', 'MaPhong', 'NgayNhanPhong', 'NgayTraPhong', 'TongTien', 'TrangThai', 'ThanhToan'];

    protected $casts = [
        'MaHD' => 'integer',
        'MaPhong' => 'integer',
        'NgayNhanPhong' => 'date:Y-m-d',
        'NgayTraPhong' => 'date:Y-m-d',
        'TongTien' => 'float',
        'TrangThai' => 'boolean',
        'ThanhToan' => 'boolean',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'MaPhong', 'MaPhong');
    }
}
