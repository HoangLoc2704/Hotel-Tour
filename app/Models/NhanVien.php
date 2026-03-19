<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table = 'tbl_NhanVien';
    protected $primaryKey = 'MaNV';
    public $timestamps = false;
    
    protected $fillable = ['TenNV', 'GioiTinh', 'NgaySinh', 'DiaChi', 'SDT', 'TenTK', 'MatKhau', 'Email', 'TrangThai', 'MaCV'];
    
    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'MaCV', 'MaCV');
    }
}
