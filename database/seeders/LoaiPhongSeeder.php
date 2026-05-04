<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiPhongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loai_phongs')->insert([
            [
                'TenLoai' => 'Phòng đơn',
                'GiaPhong' => 500000,
                'SoLuongNguoi' => 2,
                'HinhAnh' => 'Don1.jpg',
                'MoTa' => 'Phòng đơn nhà tranh dành cho tối đa 2 người, thiết kế mộc mạc, gần gũi thiên nhiên. Trang bị giường đôi, máy lạnh, quạt, tivi, WiFi và phòng tắm riêng nước nóng.',
            ],
            [
                'TenLoai' => 'Phòng đơn View',
                'GiaPhong' => 800000,
                'SoLuongNguoi' => 2,
                'HinhAnh' => 'DonView1.jpg',
                'MoTa' => 'Phòng đơn view đẹp cho 2 người, không gian thoáng với cửa sổ lớn nhìn ra cảnh quan. Trang bị giường đôi, máy lạnh, tivi, WiFi, minibar và phòng tắm riêng tiện nghi.',
            ],
            [
                'TenLoai' => 'Phòng đôi',
                'GiaPhong' => 900000,
                'SoLuongNguoi' => 4,
                'HinhAnh' => 'Doi1.jpg',
                'MoTa' => 'Phòng đôi cho 4 người, không gian rộng rãi, thiết kế truyền thống. Trang bị 2 giường lớn, máy lạnh, quạt, tivi, WiFi và phòng tắm riêng.',
            ],
            [
                'TenLoai' => 'Phòng đôi View',
                'GiaPhong' => 1300000,
                'SoLuongNguoi' => 4,
                'HinhAnh' => 'DoiView1.jpg',
                'MoTa' => 'Phòng đôi view đẹp cho 4 người, có cửa sổ hoặc ban công nhìn ra thiên nhiên. Trang bị 2 giường lớn, máy lạnh, tivi, WiFi, minibar và phòng tắm riêng.',
            ],
            [
                'TenLoai' => 'Phòng gia đình',
                'GiaPhong' => 1200000,
                'SoLuongNguoi' => 6,
                'HinhAnh' => 'GD1.jpg',
                'MoTa' => 'Phòng gia đình cho 6 người, không gian rộng, gần gũi thiên nhiên. Trang bị nhiều giường, máy lạnh, quạt, tivi, WiFi và phòng tắm riêng.',
            ],
            [
                'TenLoai' => 'Phòng gia đình View',
                'GiaPhong' => 1800000,
                'SoLuongNguoi' => 6,
                'HinhAnh' => 'GDView1.jpg',
                'MoTa' => 'Phòng gia đình view đẹp cho 6 người, không gian thoáng, tầm nhìn đẹp. Trang bị giường ngủ, máy lạnh, tivi, WiFi, minibar và phòng tắm riêng hiện đại.',
            ],
        ]);
    }
}