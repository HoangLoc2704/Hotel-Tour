<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    protected $table = 'tbl_ChucVu';
    protected $primaryKey = 'MaCV';
    public $timestamps = false;
    
    protected $fillable = ['TenCV'];
    
    public function nhanVien()
    {
        return $this->hasMany(NhanVien::class, 'MaCV', 'MaCV');
    }
}
