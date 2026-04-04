<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnhTour extends Model
{
    protected $table = 'tbl_AnhTour';
    protected $primaryKey = 'MaAT';
    public $timestamps = false;

    protected $fillable = [
        'MaTour',
        'HinhAnh',
    ];

    protected $casts = [
        'MaAT' => 'integer',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'MaTour', 'MaTour');
    }
}
