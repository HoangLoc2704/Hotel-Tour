<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DichVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dich_vus')->insert([
            [
                'TenDV' => 'Ăn sáng',
                'GiaDV' => 50000,
                'TrangThai' => 1,
            ],
            [
                'TenDV' => 'Ăn trưa',
                'GiaDV' => 100000,
                'TrangThai' => 1,
            ],
        ]);
    }
}