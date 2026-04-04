<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuongDanVien extends Model
{
    protected $table = 'tbl_HuongDanVien';
    protected $primaryKey = 'MaHDV';
    public $timestamps = false;

    protected $fillable = ['TenHDV', 'NgaySinh', 'DiaChi', 'SDT', 'TrangThai'];

    protected $casts = [
        'MaHDV' => 'integer',
        'NgaySinh' => 'date:Y-m-d',
        'TrangThai' => 'boolean',
    ];

    public function lichKhoiHanh()
    {
        return $this->hasMany(LichKhoiHanh::class, 'MaHDV', 'MaHDV');
    }
}
