<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexCategoria()
    {
        return $this->get(route('categorias.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexCategoria()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->indexCategoria()->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->coordenador()->create()->user()->first();
        $rota = $this->actingAs($user)->indexCategoria();
        $categorias = Categoria::orderBy('descricao')->paginate(10);
        $rota->assertViewIs('categorias.index')
        ->assertViewHas('categorias',$categorias);
    }
}
