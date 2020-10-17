<?php

namespace Tests\Feature\Coordenador;

use App\Coordenador;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private function destroyCoordenador(Coordenador $coordenador)
    {
        
        return $this->delete(route('coordenadores.destroy',['coordenador'=>$coordenador]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $coordenador = $this->coordenador()->create();

        $this->destroyCoordenador($coordenador)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->destroyCoordenador($coordenador)
        ->assertStatus(403);  

    }
  
    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());

        $coordenador= $this->coordenador()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->destroyCoordenador($coordenador)
        ->assertRedirect(route('users.index'));   
        $coordenador->ativo = 0;
        $array = $coordenador->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('coordenadores',$array);
    }

    /**
     * @test
     */
    public function coordenador_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $coordenador = new Coordenador();
        $coordenador->id = -1;
        $this->actingAs($user)->destroyCoordenador($coordenador)->assertNotFound();
    }

   
}
