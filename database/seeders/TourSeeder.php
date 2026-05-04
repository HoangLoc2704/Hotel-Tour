<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tours')->insert([
            [
                'MaTour' => 'T001',
                'TenTour' => 'Tour Núi Cấm 1 ngày',
                'GiaTourNguoiLon' => 500000,
                'GiaTourTreEm' => 300000,
                'ThoiLuong' => 1,
                'DiaDiemKhoiHanh' => 'Long Xuyên',
                'SoLuongKhachToiDa' => 30,
                'HinhAnh' => 'tour1.jpg',
                'MoTa' => 'Du lịch Núi Cấm',
                'LichTrinh' => 'LX - Núi Cấm',
                'TrangThai' => 1,
            ],
            [
                'MaTour' => 'T002',
                'TenTour' => 'Tour Núi Cấm lễ 30/4',
                'GiaTourNguoiLon' => 550000,
                'GiaTourTreEm' => 350000,
                'ThoiLuong' => 1,
                'DiaDiemKhoiHanh' => 'Châu Đốc',
                'SoLuongKhachToiDa' => 30,
                'HinhAnh' => 'tour2.jpg',
                'MoTa' => 'Du lịch lễ',
                'LichTrinh' => 'CD - Núi Cấm',
                'TrangThai' => 1,
            ],
        ]);
    }
}