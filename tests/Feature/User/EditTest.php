<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function editUser(User $user)
    {
        return $this->get(route('users.edit',$user));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $user = $this->user()->create();
        $this->editUser($user)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador_ou_como_atendente()
    {
        $user = $this->user()->create();
        $this->actingAs($this->user()->create())->editUser($user)->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())->editUser($user)
        ->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())->editUser($user)
        ->assertSessionHasNoErrors();
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_user()
    {
 
        $user = $this->user()->create();
        $this->actingAs($this->coordenador()->create()->user()->first())->editUser($user)
        ->assertViewIs('users.edit')
        ->assertViewHas('user',$user);
    }

    /**
     * @test
     */
    public function user_deve_existir_na_base_de_dados()
    {
        $user = new User();
        $user->id = -1;
        $this->actingAs($this->coordenador()->create()->user()->first())->editUser($user)
        ->assertNotFound();
    }

    /**
     * @test 
     * */
    public function coordenador_so_pode_ser_alterado_por_coordenador()
    {
        $this->withoutExceptionHandling();

        $userNovo = $this->coordenador()->create()->user()->first();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->editUser($userNovo)
        ->assertSessionHasErrors();
      

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->editUser($userNovo)
        ->assertSessionHasNoErrors();
        


    }

    /**
     * @test
     */
    public function atendente_so_pode_ser_alterado_por_coordenador()
    {
        $this->withoutExceptionHandling();
        $userNovo = $this->atendente()->create()->user()->first();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->editUser($userNovo)
        ->assertSessionHasErrors();
        
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->editUser($userNovo)
        ->assertSessionHasNoErrors();
        

    }

    /**
     * @test 
     * */
    public function user_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->editUser($user)
            ->assertSessionHasErrors();
    }
}
