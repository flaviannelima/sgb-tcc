<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipo_material')->unsigned();
            $table->foreign('tipo_material')->references('id')->on('tipos_material');
            $table->bigInteger('categoria')->unsigned();
            $table->foreign('categoria')->references('id')->on('categorias');
            $table->string('titulo');
            $table->bigInteger('editora')->unsigned();
            $table->foreign('editora')->references('id')->on('editoras');
            $table->integer('volume')->nullable();
            $table->text('observacao')->nullable();
            $table->string('localizacao',15);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obras');
    }
}
