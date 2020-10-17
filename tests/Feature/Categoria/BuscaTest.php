<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaCategoria(Categoria $categoria)
    {
        return $this->post(route('categorias.busca'),["descricao"=>$categoria->descricao]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $categoria = $this->categoria()->create();
        $this->buscaCategoria($categoria)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $categoria = $this->categoria()->create();
        $this->actingAs($user)->buscaCategoria($categoria)->assertStatus(403);
    }

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_busca()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = $this->categoria()->create();
        $rota = $this->actingAs($user)->buscaCategoria($categoria);
        $categorias = Categoria::where('descricao','LIKE','%'.$categoria->descricao.'%')
        ->orderBy('descricao')->paginate(10);
        $rota->assertViewIs('categorias.index')
        ->assertViewHas('categorias',$categorias);
    }
}
