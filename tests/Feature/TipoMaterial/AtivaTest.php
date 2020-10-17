<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    /** @param TipoMaterial $tipoMaterial 
     * @return TipoMaterial */
    private function ativaTipoMaterial(TipoMaterial $tipoMaterial)
    {
        
        return $this->post(route('tiposmaterial.ativa',['tipoMaterial'=>$tipoMaterial]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        /**@var TipoMaterial $tipoMaterial */
        $tipoMaterial = $this->tipoMaterial()->create();

        $this->ativaTipoMaterial($tipoMaterial)->assertRedirect('/login');
        
        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaTipoMaterial($tipoMaterial)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function tipo_de_material_deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var TipoMaterial $tipoMaterial */
        $tipoMaterial= $this->tipoMaterial()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaTipoMaterial($tipoMaterial)
        ->assertRedirect(route('tiposmaterial.index'));   
        $tipoMaterial->ativo = true;
        $array = $tipoMaterial->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('tipos_material',$array);
    }

    /**
     * @test
     */
    public function tipo_de_material_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $tipoMaterial = new TipoMaterial();
        $tipoMaterial->id = -5;
        $this->actingAs($user)->ativaTipoMaterial($tipoMaterial)->assertNotFound();
    }
}
