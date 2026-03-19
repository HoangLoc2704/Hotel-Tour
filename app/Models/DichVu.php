<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    protected $table = 'tbl_DichVu';
    protected $primaryKey = 'MaDV';
    public $timestamps = false;
    
    protected $fillable = ['TenDV', 'GiaDV', 'TrangThai'];
}
