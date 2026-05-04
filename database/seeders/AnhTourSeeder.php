<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnhTourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anhTours = [
            // T001
            ['MaTour' => 'T001', 'HinhAnh' => 'TourNuiCam1.jpg'],
            ['MaTour' => 'T001', 'HinhAnh' => 'TourNuiCam2.jpg'],
            ['MaTour' => 'T001', 'HinhAnh' => 'TourNuiCam3.jpg'],
            ['MaTour' => 'T001', 'HinhAnh' => 'TourNuiCam4.jpg'],
            ['MaTour' => 'T001', 'HinhAnh' => 'TourNuiCam5.jpg'],

            // T002
            ['MaTour' => 'T002', 'HinhAnh' => 'Tour30_4_1.jpg'],
            ['MaTour' => 'T002', 'HinhAnh' => 'Tour30_4_2.jpg'],
            ['MaTour' => 'T002', 'HinhAnh' => 'Tour30_4_3.jpg'],
            ['MaTour' => 'T002', 'HinhAnh' => 'Tour30_4_4.jpg'],
            ['MaTour' => 'T002', 'HinhAnh' => 'Tour30_4_5.jpg'],
        ];

        DB::table('anh_tours')->insert($anhTours);
    }
}