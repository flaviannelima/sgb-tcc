<?php

namespace Tests\Feature\User;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaUser(User $user)
    {
        
        return $this->post(route('users.ativa',['user'=>$user]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $user = $this->user()->create();

        $this->ativaUser($user)->assertRedirect('/login');
        
        $this->actingAs($this->user()->create())
        ->ativaUser($user)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
 
        $this->withoutExceptionHandling();
        Carbon::setTestNow(now());
        $user= $this->user()->create();
        
        $this->actingAs($this->coordenador()->create()->user()->first())
        ->ativaUser($user)
        ->assertRedirect(route('users.index'));   
        $user->ativo = true;
        $array = $user->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('users',$array);
    }

    /**
     * @test
     */
    public function user_deve_existir_na_base_de_dados()
    {

        $user = new User();
        $user->id = -1;
        $this->actingAs($this->coordenador()->create()->user()->first())->ativaUser($user)
        ->assertNotFound();
    }
}
