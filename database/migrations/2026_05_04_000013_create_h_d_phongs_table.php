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
        Schema::create('h_d_phongs', function (Blueprint $table) {
            $table->unsignedBigInteger('MaHD');
            $table->unsignedBigInteger('MaPhong');
            $table->date('NgayNhanPhong');
            $table->date('NgayTraPhong');
            $table->double('TongTien');
            $table->boolean('TrangThai')->default(1);
            $table->boolean('ThanhToan')->default(0);
            $table->primary(['MaHD', 'MaPhong']);
            $table->foreign('MaHD')->references('MaHD')->on('hoa_dons');
            $table->foreign('MaPhong')->references('MaPhong')->on('phongs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_d_phongs');
    }
};