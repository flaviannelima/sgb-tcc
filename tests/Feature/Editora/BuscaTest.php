<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaEditora(Editora $editora)
    {
        return $this->post(route('editoras.busca'),["nome"=>$editora->nome]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $editora = $this->editora()->create();
        $this->buscaEditora($editora)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $editora = $this->editora()->create();
        $this->actingAs($user)->buscaEditora($editora)->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_busca()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = $this->editora()->create();
        $rota = $this->actingAs($user)->buscaEditora($editora);
        $editoras = Editora::where('nome','LIKE','%'.$editora->nome.'%')
        ->orderBy('nome')->paginate(10);
        $rota->assertViewIs('editoras.index')
        ->assertViewHas('editoras',$editoras);
    }
}
