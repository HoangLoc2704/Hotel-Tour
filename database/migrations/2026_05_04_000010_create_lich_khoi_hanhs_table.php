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
        Schema::create('lich_khoi_hanhs', function (Blueprint $table) {
            $table->id('MaLKH');
            $table->string('MaTour', 20);
            $table->date('NgayKhoiHanh');
            $table->date('NgayKetThuc');
            $table->integer('SoChoConLai');
            $table->unsignedBigInteger('MaHDV');
            $table->string('TaiXe', 100);
            $table->string('PhuongTien', 100);
            $table->foreign('MaTour')->references('MaTour')->on('tours');
            $table->foreign('MaHDV')->references('MaHDV')->on('huong_dan_viens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_khoi_hanhs');
    }
};