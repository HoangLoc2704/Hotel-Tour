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
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->id('MaKH');
            $table->string('TenKH', 50);
            $table->boolean('GioiTinh')->default(1);
            $table->string('SDT', 10);
            $table->string('MatKhau', 255)->nullable();
            $table->boolean('TrangThai')->default(1);
            $table->string('Email', 100)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hangs');
    }
};