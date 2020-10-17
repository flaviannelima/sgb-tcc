<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexAssunto()
    {
        return $this->get(route('assuntos.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexAssunto()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->indexAssunto()->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->coordenador()->create()->user()->first();
        $rota = $this->actingAs($user)->indexAssunto();
        $assuntos = Assunto::orderBy('descricao')->paginate(10);
        $rota->assertViewIs('assuntos.index')
        ->assertViewHas('assuntos',$assuntos);
    }
}
