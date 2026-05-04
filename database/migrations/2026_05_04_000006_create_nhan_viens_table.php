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
        Schema::create('nhan_viens', function (Blueprint $table) {
            $table->id('MaNV');
            $table->string('TenNV', 50);
            $table->boolean('GioiTinh')->default(1);
            $table->date('NgaySinh')->nullable();
            $table->string('DiaChi', 255)->nullable();
            $table->string('SDT', 10)->nullable();
            $table->string('TenTK', 100)->unique();
            $table->string('MatKhau', 255);
            $table->string('Email', 100)->unique()->nullable();
            $table->boolean('TrangThai')->default(1);
            $table->unsignedBigInteger('MaCV');
            $table->foreign('MaCV')->references('MaCV')->on('chuc_vus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};