<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nhan_viens')->insert([
            [
                'TenNV' => 'Nguyễn Văn A',
                'GioiTinh' => 1,
                'NgaySinh' => '1995-05-10',
                'DiaChi' => 'An Giang',
                'SDT' => '0911111111',
                'TenTK' => 'nva',
                'MatKhau' => '$2y$12$obUGiKARcGFyKsU9ZN.fQ.OBFylLvfaBSVZED7LOKylAveNcQILda',
                'Email' => null,
                'TrangThai' => 1,
                'MaCV' => 1,
            ],
            [
                'TenNV' => 'Trần Thị B',
                'GioiTinh' => 0,
                'NgaySinh' => '1998-03-15',
                'DiaChi' => 'Cần Thơ',
                'SDT' => '0922222222',
                'TenTK' => 'ttb',
                'MatKhau' => '$2y$12$obUGiKARcGFyKsU9ZN.fQ.OBFylLvfaBSVZED7LOKylAveNcQILda',
                'Email' => 'b@gmail.com',
                'TrangThai' => 1,
                'MaCV' => 2,
            ],
            [
                'TenNV' => 'Lê Văn C',
                'GioiTinh' => 1,
                'NgaySinh' => '1993-07-20',
                'DiaChi' => 'Đồng Tháp',
                'SDT' => '0933333333',
                'TenTK' => 'lvc',
                'MatKhau' => '$2y$12$obUGiKARcGFyKsU9ZN.fQ.OBFylLvfaBSVZED7LOKylAveNcQILda',
                'Email' => 'c@gmail.com',
                'TrangThai' => 1,
                'MaCV' => 3,
            ],
        ]);
    }
}
