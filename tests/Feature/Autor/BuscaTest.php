<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaAutor(Autor $autor)
    {
        return $this->post(route('autores.busca'),["nome"=>$autor->nome]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $autor = $this->autor()->create();
        $this->buscaAutor($autor)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $autor = $this->autor()->create();
        $this->actingAs($user)->buscaAutor($autor)->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_busca()
    {
        $user = $this->coordenador()->create()->user()->first();
        $autor = $this->autor()->create();
        $rota = $this->actingAs($user)->buscaAutor($autor);
        $autores = Autor::where('nome','LIKE','%'.$autor->nome.'%')
        ->orderBy('nome')->paginate(10);
        $rota->assertViewIs('autores.index')
        ->assertViewHas('autores',$autores);
    }
}
