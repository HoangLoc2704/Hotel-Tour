<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'tbl_HoaDon';
    protected $primaryKey = 'MaHD';
    public $timestamps = false;
    
    protected $fillable = ['MaKH', 'NgayTao', 'ThanhTien', 'TrangThai', 'ThanhToan'];
    
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }

    public function hdPhongs()
    {
        return $this->hasMany(HDPhong::class, 'MaHD', 'MaHD');
    }

    public function hdDichVus()
    {
        return $this->hasMany(HDDichVu::class, 'MaHD', 'MaHD');
    }

    public function hdTours()
    {
        return $this->hasMany(HDTOUR::class, 'MaHD', 'MaHD');
    }

    public static function syncDetailThanhToan(string $maHD, int $thanhToan): void
    {
        $value = $thanhToan ? 1 : 0;

        HDPhong::query()
            ->where('MaHD', $maHD)
            ->update(['ThanhToan' => $value]);

        HDDichVu::query()
            ->where('MaHD', $maHD)
            ->update(['ThanhToan' => $value]);

        HDTOUR::query()
            ->where('MaHD', $maHD)
            ->update(['ThanhToan' => $value]);
    }

    public static function recalculateThanhTien(string $maHD): void
    {
        $hoaDon = static::query()->find($maHD);
        if (!$hoaDon) {
            return;
        }

        $tongDichVu = (float) HDDichVu::query()
            ->where('MaHD', $maHD)
            ->sum('TongTien');

        $tongPhong = (float) HDPhong::query()
            ->where('MaHD', $maHD)
            ->sum('TongTien');

        $tongTour = (float) HDTOUR::query()
            ->where('MaHD', $maHD)
            ->sum('TongTien');

        $hasAnyDetail = HDPhong::query()->where('MaHD', $maHD)->exists()
            || HDDichVu::query()->where('MaHD', $maHD)->exists()
            || HDTOUR::query()->where('MaHD', $maHD)->exists();

        $hasUnpaidDetail = HDPhong::query()
            ->where('MaHD', $maHD)
            ->where(function ($query) {
                $query->whereNull('ThanhToan')
                    ->orWhere('ThanhToan', 0);
            })
            ->exists()
            || HDDichVu::query()
                ->where('MaHD', $maHD)
                ->where(function ($query) {
                    $query->whereNull('ThanhToan')
                        ->orWhere('ThanhToan', 0);
                })
                ->exists()
            || HDTOUR::query()
                ->where('MaHD', $maHD)
                ->where(function ($query) {
                    $query->whereNull('ThanhToan')
                        ->orWhere('ThanhToan', 0);
                })
                ->exists();

        $hoaDon->ThanhTien = $tongDichVu + $tongPhong + $tongTour;
        $hoaDon->ThanhToan = ($hasAnyDetail && !$hasUnpaidDetail) ? 1 : 0;
        $hoaDon->save();
    }
}
