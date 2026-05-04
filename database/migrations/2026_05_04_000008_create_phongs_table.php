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
        Schema::create('phongs', function (Blueprint $table) {
            $table->id('MaPhong');
            $table->string('TenPhong', 10);
            $table->unsignedBigInteger('MaLoai');
            $table->foreign('MaLoai')->references('MaLoai')->on('loai_phongs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phongs');
    }
};