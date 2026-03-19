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
        DB::table('tbl_NhanVien')->insert([
            [
                'TenNV' => 'Trịnh Hoàng Lộc',
                'GioiTinh' => 1,
                'NgaySinh' => '2004-04-27',
                'DiaChi' => '123 Đường ABC, TP.HCM',
                'SDT' => '0901234567',
                'TenTK' => 'admin',
                'MatKhau' => Hash::make('123456'), // Mật khẩu: 123456
                'Email' => 'admin@hotel.com',
                'TrangThai' => 1,
                'MaCV' => 1,
            ],
            [
                'TenNV' => 'Nguyễn Văn A',
                'GioiTinh' => 1,
                'NgaySinh' => '1990-01-01',
                'DiaChi' => '456 Đường XYZ, Hà Nội',
                'SDT' => '0987654321',
                'TenTK' => 'nhanvien1',
                'MatKhau' => Hash::make('123456'),
                'Email' => 'nhanvien1@hotel.com',
                'TrangThai' => 1,
                'MaCV' => 2,
            ],
        ]);
    }
}
