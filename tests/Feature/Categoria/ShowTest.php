<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showCategoria(Categoria $categoria)
    {
        return $this->get(route('categorias.show',$categoria));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $categoria = $this->categoria()->create();
        $this->showCategoria($categoria)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $categoria = $this->categoria()->create();
        $this->actingAs($user)->showCategoria($categoria)->assertStatus(403);
    }

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_categoria()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = $this->categoria()->create();
        $this->actingAs($user)->showCategoria($categoria)->assertViewIs('categorias.show')
        ->assertViewHas('categoria',$categoria);
    }

    /**
     * @test
     */
    public function categoria_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = new Categoria();
        $categoria->id = -1;
        $this->actingAs($user)->showCategoria($categoria)->assertNotFound();
    }
}
