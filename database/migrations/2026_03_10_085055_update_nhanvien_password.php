<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật mật khẩu thành hash của "123456"
        DB::table('tbl_NhanVien')
            ->where('Email', 'admin@hotel.com')
            ->update(['MatKhau' => Hash::make('123456')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần
    }
};
