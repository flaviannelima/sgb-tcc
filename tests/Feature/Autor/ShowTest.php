<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showAutor(Autor $autor)
    {
        return $this->get(route('autores.show',$autor));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $autor = $this->autor()->create();
        $this->showAutor($autor)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $autor = $this->autor()->create();
        $this->actingAs($user)->showAutor($autor)->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_autor()
    {
        $user = $this->coordenador()->create()->user()->first();
        $autor = $this->autor()->create();
        $this->actingAs($user)->showAutor($autor)->assertViewIs('autores.show')
        ->assertViewHas('autor',$autor);
    }

    /**
     * @test
     */
    public function autor_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $autor = new Autor();
        $autor->id = -1;
        $this->actingAs($user)->showAutor($autor)->assertNotFound();
    }
}
