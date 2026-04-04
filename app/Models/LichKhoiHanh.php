<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichKhoiHanh extends Model
{
    protected $table = 'tbl_LichKhoiHanh';
    protected $primaryKey = 'MaLKH';
    public $timestamps = false;

    protected $fillable = ['MaTour', 'NgayKhoiHanh', 'NgayKetThuc', 'SoChoConLai', 'MaHDV', 'TaiXe', 'PhuongTien'];

    protected $casts = [
        'MaLKH' => 'integer',
        'NgayKhoiHanh' => 'date:Y-m-d',
        'NgayKetThuc' => 'date:Y-m-d',
        'SoChoConLai' => 'integer',
        'MaHDV' => 'integer',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'MaTour', 'MaTour');
    }

    public function huongDanVien()
    {
        return $this->belongsTo(HuongDanVien::class, 'MaHDV', 'MaHDV');
    }
}
