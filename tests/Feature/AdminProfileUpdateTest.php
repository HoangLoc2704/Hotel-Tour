<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminProfileUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('tbl_NhanVien');
        Schema::dropIfExists('tbl_ChucVu');

        Schema::create('tbl_ChucVu', function (Blueprint $table) {
            $table->increments('MaCV');
            $table->string('TenCV');
        });

        Schema::create('tbl_NhanVien', function (Blueprint $table) {
            $table->increments('MaNV');
            $table->string('TenNV');
            $table->boolean('GioiTinh')->nullable();
            $table->date('NgaySinh')->nullable();
            $table->string('DiaChi')->nullable();
            $table->string('SDT')->nullable();
            $table->string('TenTK')->nullable();
            $table->string('MatKhau')->nullable();
            $table->string('Email')->nullable();
            $table->boolean('TrangThai')->default(1);
            $table->unsignedInteger('MaCV')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('tbl_NhanVien');
        Schema::dropIfExists('tbl_ChucVu');

        parent::tearDown();
    }

    public function test_logged_in_admin_can_update_profile_and_change_password(): void
    {
        DB::table('tbl_ChucVu')->insert([
            'MaCV' => 1,
            'TenCV' => 'Quản lý',
        ]);

        DB::table('tbl_NhanVien')->insert([
            'MaNV' => 1,
            'TenNV' => 'Admin Cu',
            'GioiTinh' => 1,
            'NgaySinh' => '1995-01-15',
            'DiaChi' => 'TP.HCM',
            'SDT' => '0909123456',
            'TenTK' => 'admin',
            'MatKhau' => Hash::make('secret123'),
            'Email' => 'admin@example.com',
            'TrangThai' => 1,
            'MaCV' => 1,
        ]);

        $response = $this->withSession([
            'user_id' => 1,
            'user_name' => 'Admin Cu',
            'user_email' => 'admin@example.com',
            'user_role' => 1,
            'user_role_name' => 'Quản lý',
        ])->patch(route('admin.profile.update'), [
            'TenNV' => 'Admin Mới',
            'GioiTinh' => '0',
            'NgaySinh' => '1996-02-20',
            'DiaChi' => 'Cần Thơ',
            'SDT' => '0911222333',
            'TenTK' => 'admin-moi',
            'Email' => 'adminmoi@example.com',
            'current_password' => 'secret123',
            'MatKhau' => 'newsecret456',
            'MatKhau_confirmation' => 'newsecret456',
        ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_NhanVien', [
            'MaNV' => 1,
            'TenNV' => 'Admin Mới',
            'GioiTinh' => 0,
            'NgaySinh' => '1996-02-20',
            'DiaChi' => 'Cần Thơ',
            'SDT' => '0911222333',
            'TenTK' => 'admin-moi',
            'Email' => 'adminmoi@example.com',
        ]);

        $updatedPasswordHash = DB::table('tbl_NhanVien')->where('MaNV', 1)->value('MatKhau');
        $this->assertTrue(Hash::check('newsecret456', (string) $updatedPasswordHash));
    }
}
