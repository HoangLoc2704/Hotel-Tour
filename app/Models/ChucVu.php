<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    protected $table = 'tbl_ChucVu';
    protected $primaryKey = 'MaCV';
    public $timestamps = false;

    protected $fillable = ['TenCV'];

    protected $casts = [
        'MaCV' => 'integer',
    ];

    public function nhanViens()
    {
        return $this->hasMany(NhanVien::class, 'MaCV', 'MaCV');
    }

    public function nhanVien()
    {
        return $this->nhanViens();
    }
}
