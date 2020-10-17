<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexEditora()
    {
        return $this->get(route('editoras.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexEditora()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->indexEditora()->assertStatus(403);
    }

 

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->coordenador()->create()->user()->first();
        $rota = $this->actingAs($user)->indexEditora();
        $editoras = Editora::orderBy('nome')->paginate(10);
        $rota->assertViewIs('editoras.index')
        ->assertViewHas('editoras',$editoras);
    }
}
