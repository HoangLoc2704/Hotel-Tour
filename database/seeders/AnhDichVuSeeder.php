<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnhDichVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anhDichVus = [
            // MaDV 1
            ['MaDV' => 1, 'HinhAnh' => 'DichVu_1_1.jpg'],
            ['MaDV' => 1, 'HinhAnh' => 'DichVu_1_2.jpg'],
            ['MaDV' => 1, 'HinhAnh' => 'DichVu_1_3.jpg'],

            // MaDV 2
            ['MaDV' => 2, 'HinhAnh' => 'DichVu_1_1.jpg'],
            ['MaDV' => 2, 'HinhAnh' => 'DichVu_2_2.jpg'],
            ['MaDV' => 2, 'HinhAnh' => 'DichVu_2_3.jpg'],
        ];

        DB::table('anh_dich_vus')->insert($anhDichVus);
    }
}