<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showEditora(Editora $editora)
    {
        return $this->get(route('editoras.show',$editora));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $editora = $this->editora()->create();
        $this->showEditora($editora)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $editora = $this->editora()->create();
        $this->actingAs($user)->showEditora($editora)->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_editora()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = $this->editora()->create();
        $this->actingAs($user)->showEditora($editora)->assertViewIs('editoras.show')
        ->assertViewHas('editora',$editora);
    }

    /**
     * @test
     */
    public function editora_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = new Editora();
        $editora->id = -1;
        $this->actingAs($user)->showEditora($editora)->assertNotFound();
    }
}
