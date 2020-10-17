<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function editCategoria(Categoria $categoria)
    {
        return $this->get(route('categorias.edit',$categoria));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $categoria = $this->categoria()->create();
        $this->editCategoria($categoria)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $categoria = $this->categoria()->create();
        $this->actingAs($user)->editCategoria($categoria)->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_categoria()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = $this->categoria()->create();
        $this->actingAs($user)->editCategoria($categoria)->assertViewIs('categorias.edit')
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
        $this->actingAs($user)->editCategoria($categoria)->assertNotFound();
    }

    /**
     * @test
     */
    public function categoria_deve_estar_ativo()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = $this->categoria()->create();
        $categoria->ativo = false;
        $categoria->save();
        $this->actingAs($user)->editCategoria($categoria)
        ->assertSessionHasErrors();
    }
}
