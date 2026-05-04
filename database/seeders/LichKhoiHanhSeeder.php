<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LichKhoiHanhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lich_khoi_hanhs')->insert([
            [
                'MaTour' => 'T001',
                'NgayKhoiHanh' => '2026-06-01',
                'NgayKetThuc' => '2026-06-02',
                'SoChoConLai' => 30,
                'MaHDV' => 1,
                'TaiXe' => 'Nguyễn Văn A',
                'PhuongTien' => 'Xe 29 chỗ',
            ],
            [
                'MaTour' => 'T002',
                'NgayKhoiHanh' => '2026-04-30',
                'NgayKetThuc' => '2026-05-01',
                'SoChoConLai' => 30,
                'MaHDV' => 2,
                'TaiXe' => 'Trần Văn B',
                'PhuongTien' => 'Xe 45 chỗ',
            ],
        ]);
    }
}