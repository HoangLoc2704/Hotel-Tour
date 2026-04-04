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
        Schema::create('tbl_AnhTour', function (Blueprint $table) {
            $table->increments('MaAT');
            $table->string('MaTour', 20);
            $table->string('HinhAnh', 255)->nullable();

            $table->foreign('MaTour')
                  ->references('MaTour')
                  ->on('tbl_Tour')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_AnhTour');
    }
};