<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmprestimosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exemplar')->unsigned();
            $table->foreign('exemplar')->on('exemplares')->references('id');
            $table->bigInteger('leitor')->unsigned();

            $table->bigInteger('usuario_emprestou')->unsigned();
            $table->foreign('usuario_emprestou')->on('users')->references('id');
            $table->bigInteger('usuario_devolveu')->unsigned()->nullable();
            $table->date('data_prevista_devolucao');
            $table->date('data_devolucao')->nullable();
            $table->foreign('usuario_devolveu')->on('users')->references('id');
            $table->foreign('leitor')->on('leitores')->references('id');
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
        Schema::dropIfExists('emprestimos');
    }
}
