<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateUser(User $user, User $userNovo,$confirma = null)
    {
        $array =$userNovo->makeVisible(['password'])->toArray();
        $array["password_confirmation"]  = (!$confirma)?$userNovo->password : $confirma;


        return $this->patch(route('users.update',['user' => $user]),$array);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador_ou_atendente()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->make();
        $this->updateUser($userAntigo,$userNovo)->assertRedirect('/login');
      
        $this->withoutExceptionHandling();
        $user = $this->user()->create();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertStatus(403);  
   
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)->updateUser($userAntigo,$userNovo);
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseHas('users',$userNovo->toArray());

        $userNovo = $this->user()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->updateUser($userAntigo,$userNovo);
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseHas('users',$userNovo->toArray());

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertRedirect(route('users.index'));   
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseHas('users', $userNovo->toArray());
    }
    /**
     * @test 
     * */
    public function campo_name_obrigatorio()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->setName('')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors(['name' => trans('validation.required',['attribute' => 'nome'])]);
    }

    /**
     * @test 
     * */
    public function campo_email_obrigatorio()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->setEmail('')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors(['email' => trans('validation.required',['attribute' => 'email'])]);
    }

    /**
     * @test 
     * */
    public function campo_email_deve_ser_um_email()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->setEmail('rfafr.com')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors(['email' => trans('validation.email',['attribute' => 'email'])]);
    }
    /**
     * @test
     */
    public function campo_email_deve_ser_unico()
    {
      
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->setEmail($this->user()->create()->email)->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors(['email' => trans('validation.unique',['attribute' => 'email'])]);
    }

    /**
     * @test 
     * */
    public function campo_password_opcional()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->setPassword('')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasNoErrors();

        $userNovo->makeHidden(['password']);
        $this->assertDatabaseHas('users', $userNovo->toArray());

        $userAntigo->makeHidden(['password']);
        $this->assertDatabaseMissing('users',$userAntigo->toArray());
    }

     /**
     * @test 
     * */
    public function campo_password_deve_ter_no_minimo_8_caracteres()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->setPassword('teste')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors(['password' => trans('validation.min.string',['attribute' => 'senha',
        'min' => 8])]);
    }

    /**
     * @test 
     * */
    public function campo_password_deve_ser_confirmado()
    {
        $userAntigo = $this->user()->create();
        $userNovo = $this->user()->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo,'testando123')
        ->assertSessionHasErrors(['password' => trans('validation.confirmed',['attribute' => 'senha'])]);
    }

    /**
     * @test 
     * */
    public function coordenador_so_pode_ser_alterado_por_coordenador()
    {
        $this->withoutExceptionHandling();
        $userAntigo = $this->coordenador()->create()->user()->first();
        $userNovo = $this->user()->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors();
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseMissing('users',$userNovo->toArray());

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasNoErrors();
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseHas('users',$userNovo->toArray());


    }

    /**
     * @test
     */
    public function atendente_so_pode_ser_alterado_por_coordenador()
    {
        $this->withoutExceptionHandling();
        $userAntigo = $this->atendente()->create()->user()->first();
        $userNovo = $this->user()->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasErrors();
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseMissing('users',$userNovo->toArray());

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateUser($userAntigo,$userNovo)
        ->assertSessionHasNoErrors();
        $userNovo->makeHidden(['password']);
        $this->assertDatabaseHas('users',$userNovo->toArray());


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
            ->updateUser($user,$this->user()->make())
            ->assertSessionHasErrors();
    }
}
