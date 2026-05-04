<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HuongDanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('huong_dan_viens')->insert([
            [
                'TenHDV' => 'Nguyễn Văn Hùng',
                'NgaySinh' => '1990-05-12',
                'DiaChi' => 'An Giang',
                'SDT' => '0901234567',
                'TrangThai' => 1,
            ],
            [
                'TenHDV' => 'Trần Thị Mai',
                'NgaySinh' => '1995-08-20',
                'DiaChi' => 'Cần Thơ',
                'SDT' => '0912345678',
                'TrangThai' => 1,
            ],
            [
                'TenHDV' => 'Lê Quốc Bảo',
                'NgaySinh' => '1988-03-15',
                'DiaChi' => 'Long Xuyên',
                'SDT' => '0987654321',
                'TrangThai' => 1,
            ],
        ]);
    }
}