<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function editTipoMaterial(TipoMaterial $tipoMaterial)
    {
        return $this->get(route('tiposmaterial.edit',$tipoMaterial));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $tipoMaterial = $this->tipoMaterial()->create();
        $this->editTipoMaterial($tipoMaterial)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $tipoMaterial = $this->tipoMaterial()->create();
        $this->actingAs($user)->editTipoMaterial($tipoMaterial)->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_tipo_de_material()
    {
        $user = $this->coordenador()->create()->user()->first();
        $tipoMaterial = $this->tipoMaterial()->create();
        $this->actingAs($user)->editTipoMaterial($tipoMaterial)->assertViewIs('tiposmaterial.edit')
        ->assertViewHas('tipoMaterial',$tipoMaterial);
    }

    /**
     * @test
     */
    public function tipo_de_material_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $tipoMaterial = new TipoMaterial();
        $tipoMaterial->id = -1;
        $this->actingAs($user)->editTipoMaterial($tipoMaterial)->assertNotFound();
    }

    /**
     * @test 
     * */
    public function tipo_material_deve_estar_ativo()
    {

        $user = $this->coordenador()->create()->user()->first();
        $tipoMaterial = $this->tipoMaterial()->create();
        $tipoMaterial->ativo = false;
        $tipoMaterial->save();
        $this->actingAs($user)
            ->editTipoMaterial($tipoMaterial)
            ->assertSessionHasErrors();
    }

}
