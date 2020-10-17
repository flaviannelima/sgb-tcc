<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateTipoMaterial(TipoMaterial $tipoMaterial, TipoMaterial $tipoMaterialNovo)
    {
        
        return $this->patch(route('tiposmaterial.update',['tipomaterial'=>$tipoMaterial]),
        $tipoMaterialNovo->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $tipoMaterial = $this->tipoMaterial()->create()->first();
        $tipoMaterialNovo = $this->tipoMaterial()->make();
        $this->updateTipoMaterial($tipoMaterial,$tipoMaterialNovo)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->updateTipoMaterial($tipoMaterial,$tipoMaterialNovo)
        ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var TipoMaterial $tipoMaterialAntigo */
        $tipoMaterialAntigo = $this->tipoMaterial()->create();
        /**@var TipoMaterial $tipoMaterialNovo */
        $tipoMaterialNovo = $this->tipoMaterial()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateTipoMaterial($tipoMaterialAntigo, $tipoMaterialNovo)
        ->assertRedirect(route('tiposmaterial.index'));   

        $this->assertDatabaseHas('tipos_material',[
            'descricao' => $tipoMaterialNovo->descricao,
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_descricao_obrigatorio()
    {
        /**@var TipoMaterial $tipoMaterialAntigo */
        $tipoMaterialAntigo = $this->tipoMaterial()->create();
        /**@var TipoMaterial $tipoMaterialNovo */
        $tipoMaterialNovo = $this->tipoMaterial()->setDescricao('')->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateTipoMaterial($tipoMaterialAntigo,$tipoMaterialNovo)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.required',['attribute' => 'descricao'])]);
    }
    /**
     * @test
     */
    public function campo_descricao_deve_ser_unico()
    {

        $tipoMaterialAntigo = $this->tipoMaterial()->setDescricao('teste')->create();
        $this->tipoMaterial()->setDescricao('testenovo')->create();

        $tipoMaterialNovo= $this->tipoMaterial()->setDescricao('testenovo')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateTipoMaterial($tipoMaterialAntigo,$tipoMaterialNovo)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.unique',['attribute' => 'descricao'])]);
    }

    /**
     * @test
     */
    public function tipo_de_material_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $tipoMaterial = new TipoMaterial();
        $tipoMaterialNovo = $this->tipoMaterial()->make();
        $tipoMaterial->id = -1;
        $this->actingAs($user)->updateTipoMaterial($tipoMaterial,$tipoMaterialNovo)
        ->assertNotFound();
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
            ->updateTipoMaterial($tipoMaterial,$this->tipoMaterial()->make())
            ->assertSessionHasErrors();
    }
}
