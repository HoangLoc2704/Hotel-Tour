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
        Schema::create('loai_phongs', function (Blueprint $table) {
            $table->id('MaLoai');
            $table->string('TenLoai', 50);
            $table->double('GiaPhong');
            $table->integer('SoLuongNguoi');
            $table->string('HinhAnh', 255)->nullable();
            $table->string('MoTa', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_phongs');
    }
};