<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexAutor()
    {
        return $this->get(route('autores.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexAutor()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->indexAutor()->assertStatus(403);
    }

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->coordenador()->create()->user()->first();
        $rota = $this->actingAs($user)->indexAutor();
        $autores = Autor::orderBy('nome')->paginate(10);
        $rota->assertViewIs('autores.index')
        ->assertViewHas('autores',$autores);
    }
}
