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
        Schema::create('tours', function (Blueprint $table) {
            $table->string('MaTour', 20)->primary();
            $table->string('TenTour', 100);
            $table->double('GiaTourNguoiLon');
            $table->double('GiaTourTreEm');
            $table->integer('ThoiLuong');
            $table->string('DiaDiemKhoiHanh', 255);
            $table->integer('SoLuongKhachToiDa');
            $table->string('HinhAnh', 255)->nullable();
            $table->string('MoTa', 255)->nullable();
            $table->string('LichTrinh', 255)->nullable();
            $table->boolean('TrangThai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};