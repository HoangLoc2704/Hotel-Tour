<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnhPhong extends Model
{
    protected $table = 'tbl_AnhPhong';
    protected $primaryKey = 'MaAP';
    public $timestamps = false;

    protected $fillable = [
        'MaLoai',
        'HinhAnh',
    ];

    protected $casts = [
        'MaAP' => 'integer',
        'MaLoai' => 'integer',
    ];

    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'MaLoai', 'MaLoai');
    }
}
