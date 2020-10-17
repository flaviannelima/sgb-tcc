<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssuntosObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assuntos_obras', function (Blueprint $table) {
            $table->bigInteger('obra')->unsigned();
            $table->foreign('obra')->references('id')->on('obras');
            $table->bigInteger('assunto')->unsigned();
            $table->foreign('assunto')->references('id')->on('assuntos');
            $table->primary(['obra','assunto']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assuntos_obras');
    }
}
