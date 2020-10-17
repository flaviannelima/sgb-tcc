<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeTipoMaterial(TipoMaterial $tipomaterial = null)
    {
        $tipomaterial = $tipomaterial ?? collect([]);
        return $this->post(route('tiposmaterial.store'),$tipomaterial->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $tipomaterial = $this->tipoMaterial()->make();
        $this->storeTipoMaterial($tipomaterial)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeTipoMaterial($tipomaterial)
        ->assertStatus(403);  

    }
   
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var TipoMaterial $tipomaterial */
        $tipomaterial = $this->tipoMaterial()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeTipoMaterial($tipomaterial)
        ->assertRedirect(route('tiposmaterial.index'));   

        $this->assertDatabaseHas('tipos_material',[
            'descricao' => $tipomaterial->descricao,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_descricao_obrigatorio()
    {
        /**@var TipoMaterial $tipomaterial */
        $tipomaterial = $this->tipoMaterial()->setDescricao('')->make();
  
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->storeTipoMaterial($tipomaterial)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.required',['attribute' => 'descricao'])]);
    }
    /**
     * @test
     */
    public function campo_descricao_deve_ser_unico()
    {

        $this->tipoMaterial()->setDescricao('teste')->create();
        /**@var TipoMaterial $tipomaterial */
        $tipomaterial = $this->tipoMaterial()->setDescricao('teste')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeTipoMaterial($tipomaterial)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.unique',['attribute' => 'descricao'])]);
    }
}
