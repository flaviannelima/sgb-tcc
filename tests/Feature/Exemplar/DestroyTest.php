<?php

namespace Tests\Feature\Exemplar;

use App\Exemplar;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private function destroyExemplar(Exemplar $exemplar)
    {
        
        return $this->delete(route('exemplares.destroy',['exemplar'=>$exemplar]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $exemplar = $this->exemplar()->create();

        $this->destroyExemplar($exemplar)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->destroyExemplar($exemplar)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_show_obra()
    {
        Carbon::setTestNow(now());

        /**@var Exemplar $exemplar */
        $exemplar= $this->exemplar()->create();
        
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->destroyExemplar($exemplar)
        ->assertRedirect(route('obras.show',$exemplar->obra));   
        $exemplar->ativo = 0;
        $array = $exemplar->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('exemplares',$array);
    }

    /**
     * @test
     */
    public function exemplar_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $exemplar = new Exemplar();
        $exemplar->id = -1;
        $this->actingAs($user)->destroyExemplar($exemplar)->assertNotFound();
    }
}
