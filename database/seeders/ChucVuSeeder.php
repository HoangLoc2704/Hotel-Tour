<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_ChucVu')->insert([
            ['TenCV' => 'Quản lý'],
            ['TenCV' => 'Lễ tân'],
            ['TenCV' => 'Nhân viên Tour'],
            ['TenCV' => 'Kế toán'],
        ]);
    }
}
