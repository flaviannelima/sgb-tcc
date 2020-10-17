<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreTest extends TestCase
{
    
    private function storeUser(User $user,$confirma = null)
    {
        $array =$user->makeVisible(['password'])->toArray();
        $array["password_confirmation"]  = (!$confirma)?$user->password : $confirma;


        return $this->post(route('users.store'),$array);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador_ou_atendente()
    {
        $user2 = $this->user()->make();
        $this->storeUser($user2)->assertRedirect('/login');
      
        $this->withoutExceptionHandling();
        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertStatus(403);  
   
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)->storeUser($user2);
        $user2->makeHidden(['password']);
        $this->assertDatabaseHas('users',$user2->toArray());

        $user2 = $this->user()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->storeUser($user2);
        $user2->makeHidden(['password']);
        $this->assertDatabaseHas('users',$user2->toArray());

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        
        /**@var User $user */
        $user2 = $this->user()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertRedirect(route('users.index'));   
        $user2->makeHidden(['password']);
        $this->assertDatabaseHas('users', $user2->toArray());
    }
    /**
     * @test 
     * */
    public function campo_name_obrigatorio()
    {
        /**@var User $user */
        $user2 = $this->user()->setName('')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertSessionHasErrors(['name' => trans('validation.required',['attribute' => 'nome'])]);
    }

    /**
     * @test 
     * */
    public function campo_email_obrigatorio()
    {
        /**@var User $user */
        $user2 = $this->user()->setEmail('')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertSessionHasErrors(['email' => trans('validation.required',['attribute' => 'email'])]);
    }

    /**
     * @test 
     * */
    public function campo_email_deve_ser_um_email()
    {
        /**@var User $user */
        $user2 = $this->user()->setEmail('rfafr.com')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertSessionHasErrors(['email' => trans('validation.email',['attribute' => 'email'])]);
    }
    /**
     * @test
     */
    public function campo_email_deve_ser_unico()
    {

        $this->user()->setEmail('teste@gmail.com')->create();
        /**@var User $user */
        $user2 = $this->user()->setEmail('teste@gmail.com')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertSessionHasErrors(['email' => trans('validation.unique',['attribute' => 'email'])]);
    }

    /**
     * @test 
     * */
    public function campo_password_obrigatorio()
    {
        /**@var User $user */
        $user2 = $this->user()->setPassword('')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertSessionHasErrors(['password' => trans('validation.required',['attribute' => 'senha'])]);
    }

     /**
     * @test 
     * */
    public function campo_password_deve_ter_no_minimo_8_caracteres()
    {
        /**@var User $user */
        $user2 = $this->user()->setPassword('teste')->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->storeUser($user2)
        ->assertSessionHasErrors(['password' => trans('validation.min.string',['attribute' => 'senha',
        'min' => 8])]);
    }

    /**
     * @test 
     * */
    public function campo_password_deve_ser_confirmado()
    {
        /**@var User $user */
        $user2 = $this->user()->make();
  
        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)
        ->storeUser($user2,'testando123')
        ->assertSessionHasErrors(['password' => trans('validation.confirmed',['attribute' => 'senha'])]);
    }
}
