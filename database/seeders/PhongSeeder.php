<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phongs = [
            // Phòng đơn (MaLoai = 1)
            ['TenPhong' => 'P101', 'MaLoai' => 1],
            ['TenPhong' => 'P102', 'MaLoai' => 1],
            ['TenPhong' => 'P103', 'MaLoai' => 1],
            ['TenPhong' => 'P104', 'MaLoai' => 1],
            ['TenPhong' => 'P105', 'MaLoai' => 1],
            ['TenPhong' => 'P106', 'MaLoai' => 1],
            ['TenPhong' => 'P107', 'MaLoai' => 1],

            // Phòng đơn View (MaLoai = 2)
            ['TenPhong' => 'P108', 'MaLoai' => 2],
            ['TenPhong' => 'P109', 'MaLoai' => 2],
            ['TenPhong' => 'P110', 'MaLoai' => 2],

            // Phòng đôi (MaLoai = 3)
            ['TenPhong' => 'P201', 'MaLoai' => 3],
            ['TenPhong' => 'P202', 'MaLoai' => 3],
            ['TenPhong' => 'P203', 'MaLoai' => 3],
            ['TenPhong' => 'P204', 'MaLoai' => 3],
            ['TenPhong' => 'P205', 'MaLoai' => 3],
            ['TenPhong' => 'P206', 'MaLoai' => 3],
            ['TenPhong' => 'P207', 'MaLoai' => 3],

            // Phòng đôi View (MaLoai = 4)
            ['TenPhong' => 'P208', 'MaLoai' => 4],
            ['TenPhong' => 'P209', 'MaLoai' => 4],
            ['TenPhong' => 'P210', 'MaLoai' => 4],

            // Phòng gia đình (MaLoai = 5)
            ['TenPhong' => 'P301', 'MaLoai' => 5],
            ['TenPhong' => 'P302', 'MaLoai' => 5],
            ['TenPhong' => 'P303', 'MaLoai' => 5],
            ['TenPhong' => 'P304', 'MaLoai' => 5],
            ['TenPhong' => 'P305', 'MaLoai' => 5],
            ['TenPhong' => 'P306', 'MaLoai' => 5],
            ['TenPhong' => 'P307', 'MaLoai' => 5],

            // Phòng gia đình View (MaLoai = 6)
            ['TenPhong' => 'P308', 'MaLoai' => 6],
            ['TenPhong' => 'P309', 'MaLoai' => 6],
            ['TenPhong' => 'P310', 'MaLoai' => 6],
        ];

        DB::table('phongs')->insert($phongs);
    }
}