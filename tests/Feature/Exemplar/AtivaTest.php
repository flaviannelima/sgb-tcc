<?php

namespace Tests\Feature\Exemplar;

use App\Exemplar;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaExemplar(Exemplar $exemplar)
    {
        
        return $this->post(route('exemplares.ativa',['exemplar'=>$exemplar]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $exemplar = $this->exemplar()->create();

        $this->ativaExemplar($exemplar)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaExemplar($exemplar)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_show_obra()
    {
        Carbon::setTestNow(now());

        /**@var Exemplar $exemplar */
        $exemplar= $this->exemplar()->create();
        
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaExemplar($exemplar)
        ->assertRedirect(route('obras.show',$exemplar->obra));   
        $exemplar->ativo = true;
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
        $this->actingAs($user)->ativaExemplar($exemplar)->assertNotFound();
    }
}
