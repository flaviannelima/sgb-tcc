<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoresObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autores_obras', function (Blueprint $table) {
            $table->bigInteger('obra')->unsigned();
            $table->foreign('obra')->references('id')->on('obras');
            $table->bigInteger('autor')->unsigned();
            $table->foreign('autor')->references('id')->on('autores');
            $table->primary(['obra','autor']);
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autores_obras');
    }
}
