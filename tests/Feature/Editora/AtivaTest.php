<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaEditora(Editora $editora)
    {
        
        return $this->post(route('editoras.ativa',['editora'=>$editora]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $editora = $this->editora()->create();

        $this->ativaEditora($editora)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaEditora($editora)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Editora $editora */
        $editora= $this->editora()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaEditora($editora)
        ->assertRedirect(route('editoras.index'));   
        $editora->ativo = true;
        $array = $editora->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('editoras',$array);
    }

    /**
     * @test
     */
    public function editora_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = new Editora();
        $editora->id = -1;
        $this->actingAs($user)->ativaEditora($editora)->assertNotFound();
    }
}
