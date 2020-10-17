<?php

namespace Tests\Feature\Leitor;

use App\Leitor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaLeitor(Leitor $leitor)
    {
        
        return $this->post(route('leitores.ativa',['leitor'=>$leitor]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador_ou_atendente()
    {
        $leitor = $this->leitor()->create();

        $this->ativaLeitor($leitor)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaLeitor($leitor)
        ->assertStatus(403);  

        $this->actingAs($this->atendente()->create()->user()->first())
        ->ativaLeitor($this->leitor()->create())
        ->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())
        ->ativaLeitor($this->leitor()->create())
        ->assertSessionHasNoErrors();

    }
    
    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_index_users()
    {
        Carbon::setTestNow(now());
        /**@var Leitor $leitor */
        $leitor= $this->leitor()->create();
        
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaLeitor($leitor)
        ->assertRedirect(route('users.index'));   
        $leitor->ativo=true;
        $array = $leitor->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $array['data_nascimento'] = $leitor->data_nascimento->format('Y-m-d');
        $this->assertDatabaseHas('leitores',$array);
    }

    /**
     * @test
     */
    public function leitor_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $leitor = new Leitor();
        $leitor->id = -1;
        $this->actingAs($user)->ativaLeitor($leitor)->assertNotFound();
    }
}
