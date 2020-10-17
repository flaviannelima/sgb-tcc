<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExemplaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exemplares', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('codigo_barras')->unsigned()->unique();
            $table->integer('edicao')->unsigned()->nullable();
            $table->integer('ano')->unsigned()->nullable();
            $table->text('observacao')->nullable();
            $table->bigInteger('obra')->unsigned();
            $table->foreign('obra')->references('id')->on('obras');
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
        Schema::dropIfExists('exemplares');
    }
}
