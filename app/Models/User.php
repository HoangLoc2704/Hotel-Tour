<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'tbl_NhanVien';
    protected $primaryKey = 'MaNV';
    public $timestamps = false;

    protected $fillable = [
        'TenNV',
        'GioiTinh',
        'NgaySinh',
        'DiaChi',
        'SDT',
        'TenTK',
        'MatKhau',
        'Email',
        'TrangThai',
        'MaCV',
    ];

    protected $hidden = [
        'MatKhau',
    ];

    protected $casts = [
        'GioiTinh' => 'boolean',
        'NgaySinh' => 'date:Y-m-d',
        'TrangThai' => 'boolean',
        'MaCV' => 'integer',
    ];

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'MaCV', 'MaCV');
    }

    public function getAuthPassword()
    {
        return $this->MatKhau;
    }
}
