<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tbl_AnhPhong', function (Blueprint $table) {
            $table->increments('MaAP');
            $table->string('MaPhong', 10);
            $table->string('HinhAnh', 255)->nullable();

            $table->foreign('MaPhong')
                  ->references('MaPhong')
                  ->on('tbl_Phong')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_AnhPhong');
    }
};