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
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id('MaHD');
            $table->unsignedBigInteger('MaKH');
            $table->date('NgayTao');
            $table->double('ThanhTien');
            $table->boolean('TrangThai')->default(0);
            $table->boolean('ThanhToan')->default(0);
            $table->foreign('MaKH')->references('MaKH')->on('khach_hangs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};