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
        Schema::create('h_d_dich_vus', function (Blueprint $table) {
            $table->unsignedBigInteger('MaHD');
            $table->unsignedBigInteger('MaDV');
            $table->integer('SoLuong');
            $table->date('NgaySuDung');
            $table->double('TongTien');
            $table->boolean('TrangThai')->default(1);
            $table->boolean('ThanhToan')->default(0);
            $table->primary(['MaHD', 'MaDV']);
            $table->foreign('MaHD')->references('MaHD')->on('hoa_dons');
            $table->foreign('MaDV')->references('MaDV')->on('dich_vus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_d_dich_vus');
    }
};