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
        Schema::create('tbl_AnhDichVu', function (Blueprint $table) {
            $table->increments('MaADV');
            $table->integer('MaDV');
            $table->string('HinhAnh', 255)->nullable();

            $table->foreign('MaDV')
                  ->references('MaDV')
                  ->on('tbl_DichVu')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_AnhDichVu');
    }
};