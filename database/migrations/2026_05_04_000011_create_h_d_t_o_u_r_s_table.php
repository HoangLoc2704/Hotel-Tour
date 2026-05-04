<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('h_d_t_o_u_r_s', function (Blueprint $table) {
            $table->unsignedBigInteger('MaHD');
            $table->unsignedBigInteger('MaLKH');
            $table->integer('SoNguoiLon');
            $table->integer('SoTreEm');
            $table->double('TongTien');
            $table->boolean('TrangThai')->default(1);
            $table->boolean('ThanhToan')->default(0);
            $table->primary(['MaHD', 'MaLKH']);
            $table->foreign('MaHD')->references('MaHD')->on('hoa_dons');
            $table->foreign('MaLKH')->references('MaLKH')->on('lich_khoi_hanhs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_d_t_o_u_r_s');
    }
};