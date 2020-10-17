<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaAssunto(Assunto $assunto)
    {
        return $this->post(route('assuntos.busca'),["descricao"=>$assunto->descricao]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $assunto = $this->assunto()->create();
        $this->buscaAssunto($assunto)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $assunto = $this->assunto()->create();
        $this->actingAs($user)->buscaAssunto($assunto)->assertStatus(403);
    }

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_busca()
    {
        $user = $this->coordenador()->create()->user()->first();
        $assunto = $this->assunto()->create();
        $rota = $this->actingAs($user)->buscaAssunto($assunto);
        $assuntos = Assunto::where('descricao','LIKE','%'.$assunto->descricao.'%')
        ->orderBy('descricao')->paginate(10);
        $rota->assertViewIs('assuntos.index')
        ->assertViewHas('assuntos',$assuntos);
    }
}
