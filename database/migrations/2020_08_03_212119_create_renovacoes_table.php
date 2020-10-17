<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenovacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renovacoes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emprestimo')->unsigned();
            $table->foreign('emprestimo')->on('emprestimos')->references('id');
            $table->bigInteger('usuario_renovou')->unsigned();
            $table->foreign('usuario_renovou')->on('users')->references('id');
            $table->date('data_prevista_devolucao');
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
        Schema::dropIfExists('renovacoes');
    }
}
