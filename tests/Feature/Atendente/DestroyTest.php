<?php

namespace Tests\Feature\Atendente;

use App\Atendente;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private function destroyAtendente(Atendente $atendente)
    {
        
        return $this->delete(route('atendentes.destroy',['atendente'=>$atendente]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $atendente = $this->atendente()->create();

        $this->destroyAtendente($atendente)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->destroyAtendente($atendente)
        ->assertStatus(403);  

    }
  
    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $atendente= $this->atendente()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->destroyAtendente($atendente)
        ->assertRedirect(route('users.index'));   
        $atendente->ativo = false;
        $array = $atendente->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('atendentes',$array);
    }

    /**
     * @test
     */
    public function atendente_deve_existir_na_base_de_dados()
    {
        $user = $this->atendente()->create()->user()->first();
        $atendente = new Atendente();
        $atendente->id = -1;
        $this->actingAs($user)->destroyAtendente($atendente)->assertNotFound();
    }
}
