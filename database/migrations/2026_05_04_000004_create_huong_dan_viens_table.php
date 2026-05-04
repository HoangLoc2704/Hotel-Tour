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
        Schema::create('huong_dan_viens', function (Blueprint $table) {
            $table->id('MaHDV');
            $table->string('TenHDV', 50);
            $table->date('NgaySinh')->nullable();
            $table->string('DiaChi', 255);
            $table->string('SDT', 10);
            $table->boolean('TrangThai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huong_dan_viens');
    }
};