<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnhDichVu extends Model
{
    protected $table = 'tbl_AnhDichVu';
    protected $primaryKey = 'MaADV';
    public $timestamps = false;

    protected $fillable = [
        'MaDV',
        'HinhAnh',
    ];

    protected $casts = [
        'MaADV' => 'integer',
        'MaDV' => 'integer',
    ];

    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'MaDV', 'MaDV');
    }
}
