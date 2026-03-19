<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiPhong extends Model
{
    protected $table = 'tbl_LoaiPhong';
    protected $primaryKey = 'MaLoai';
    public $timestamps = false;
    
    protected $fillable = ['TenLoai'];
    
    public function phong()
    {
        return $this->hasMany(Phong::class, 'MaLoai', 'MaLoai');
    }
}
