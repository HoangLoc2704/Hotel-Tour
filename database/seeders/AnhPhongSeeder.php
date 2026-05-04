<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnhPhongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anhPhongs = [
            // MaLoai 1
            ['MaLoai' => 1, 'HinhAnh' => 'Don1.jpg'],
            ['MaLoai' => 1, 'HinhAnh' => 'Don2.jpg'],
            ['MaLoai' => 1, 'HinhAnh' => 'Don3jpg'],
            ['MaLoai' => 1, 'HinhAnh' => 'Don4.jpg'],
            ['MaLoai' => 1, 'HinhAnh' => 'Don5.jpg'],

            // MaLoai 2
            ['MaLoai' => 2, 'HinhAnh' => 'DonView1.jpg'],
            ['MaLoai' => 2, 'HinhAnh' => 'DonView2.jpg'],
            ['MaLoai' => 2, 'HinhAnh' => 'DonView3jpg'],
            ['MaLoai' => 2, 'HinhAnh' => 'DonView4.jpg'],
            ['MaLoai' => 2, 'HinhAnh' => 'DonView5.jpg'],
            ['MaLoai' => 2, 'HinhAnh' => 'DonView6.jpg'],

            // MaLoai 3
            ['MaLoai' => 3, 'HinhAnh' => 'Doi1.jpg'],
            ['MaLoai' => 3, 'HinhAnh' => 'Doi2.jpg'],
            ['MaLoai' => 3, 'HinhAnh' => 'Doi3jpg'],
            ['MaLoai' => 3, 'HinhAnh' => 'Doi4.jpg'],
            ['MaLoai' => 3, 'HinhAnh' => 'Doi5.jpg'],
            ['MaLoai' => 3, 'HinhAnh' => 'Doi6.jpg'],

            // MaLoai 4
            ['MaLoai' => 4, 'HinhAnh' => 'DoiView1.jpg'],
            ['MaLoai' => 4, 'HinhAnh' => 'DoiView2.jpg'],
            ['MaLoai' => 4, 'HinhAnh' => 'DoiView3jpg'],
            ['MaLoai' => 4, 'HinhAnh' => 'DoiView4.jpg'],
            ['MaLoai' => 4, 'HinhAnh' => 'DoiView5.jpg'],

            // MaLoai 5
            ['MaLoai' => 5, 'HinhAnh' => 'GD1.jpg'],
            ['MaLoai' => 5, 'HinhAnh' => 'GD2.jpg'],
            ['MaLoai' => 5, 'HinhAnh' => 'GD3.jpg'],
            ['MaLoai' => 5, 'HinhAnh' => 'GD4.jpg'],
            ['MaLoai' => 5, 'HinhAnh' => 'GD5.jpg'],
            ['MaLoai' => 5, 'HinhAnh' => 'GD6.jpg'],

            // MaLoai 6
            ['MaLoai' => 6, 'HinhAnh' => 'GDView1.jpg'],
            ['MaLoai' => 6, 'HinhAnh' => 'GDView2.jpg'],
            ['MaLoai' => 6, 'HinhAnh' => 'GDView3.jpg'],
            ['MaLoai' => 6, 'HinhAnh' => 'GDView4.jpg'],
            ['MaLoai' => 6, 'HinhAnh' => 'GDView5.jpg'],
        ];

        DB::table('anh_phongs')->insert($anhPhongs);
    }
}