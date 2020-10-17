<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showTipoMaterial(TipoMaterial $tipomaterial)
    {
        return $this->get(route('tiposmaterial.show',$tipomaterial));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $tipomaterial = $this->tipoMaterial()->create();
        $this->showTipoMaterial($tipomaterial)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $tipomaterial = $this->tipoMaterial()->create();
        $this->actingAs($user)->showTipoMaterial($tipomaterial)->assertStatus(403);
    }

   
    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_tipo_de_material()
    {
        $user = $this->coordenador()->create()->user()->first();
        $tipomaterial = $this->tipoMaterial()->create();
        $this->actingAs($user)->showTipoMaterial($tipomaterial)->assertViewIs('tiposmaterial.show')
        ->assertViewHas('tipomaterial',$tipomaterial);
    }

    /**
     * @test
     */
    public function tipo_de_material_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $tipomaterial = new TipoMaterial();
        $tipomaterial->id = -1;
        $this->actingAs($user)->showTipoMaterial($tipomaterial)->assertNotFound();
    }
}
