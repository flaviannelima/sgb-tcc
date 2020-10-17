<?php

namespace Tests\Feature\Obra;

use App\Obra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function editObra(Obra $obra)
    {
        return $this->get(route('obras.edit',$obra));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $obra = $this->obra()->create();
        $this->editObra($obra)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $obra = $this->obra()->create();
        $this->actingAs($user)->editObra($obra)->assertStatus(403);
    }

   
    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_obra()
    {
        $user = $this->coordenador()->create()->user()->first();
        $obra = $this->obra()->create();
        $this->actingAs($user)->editObra($obra)->assertViewIs('obras.edit')
        ->assertViewHas('obra',$obra);
    }

    /**
     * @test
     */
    public function obra_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $obra = new Obra();
        $obra->id = -1;
        $this->actingAs($user)->editObra($obra)->assertNotFound();
    }

    /**
     * @test
     */
    public function obra_deve_estar_ativo()
    {
        $obra = $this->obra()->create();
        $obra->ativo = false;
        $obra->save();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->editObra($obra)
            ->assertSessionHasErrors();
    }
}
