<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeitoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leitores', function (Blueprint $table) {
            $table->id();
            $table->string('cpf',14)->unique();
            $table->date('data_nascimento');
            $table->string('endereco');
            $table->string('telefone_residencial',14)->nullable();
            $table->string('celular',15);
            $table->bigInteger('user')->unsigned()->unique();
            $table->foreign('user')->on('users')->references('id');
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
        Schema::dropIfExists('leitores');
    }
}
