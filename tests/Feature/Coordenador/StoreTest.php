<?php

namespace Tests\Feature\Coordenador;

use App\Coordenador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeCoordenador(Coordenador $coordenador)
    {

        
        return $this->post(route('coordenadores.store'), $coordenador->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_ser_coordenador()
    {
        $coordenador = $this->coordenador()->make();

        $this->storeCoordenador($coordenador)->assertRedirect('/login');


        $user = $this->user()->create();
        $this->actingAs($user)
            ->storeCoordenador($coordenador)
            ->assertStatus(403);
    }


    /**
     * @test 
     * */
    public function campo_user_obrigatorio()
    {
        
        $coordenador = $this->coordenador()->setUser(null)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeCoordenador($coordenador)
            ->assertSessionHasErrors(['user' => trans('validation.required', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function user_deve_estar_cadastrado_previamente()
    {
        $coordenador = $this->coordenador()->setUser(-1)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeCoordenador($coordenador)
            ->assertSessionHasErrors(['user' => trans('validation.exists', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function user_deve_ser_unico()
    {
        $coordenador = $this->coordenador()->create();
        $coordenadorNovo = $this->coordenador()->setUser($coordenador->user)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeCoordenador($coordenadorNovo)
            ->assertSessionHasErrors(['user' => trans('validation.unique', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function deve_salvar_no_banco_e_redirecionar_para_pagina_index()
    {
        $this->withoutExceptionHandling();
        $coordenador = $this->coordenador()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeCoordenador($coordenador)->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('coordenadores', $coordenador->toArray());
    }

    /**
     * @test
     */
    public function user_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();
        $coordenador = $this->coordenador()->setUser($user)->make();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->storeCoordenador($coordenador)
            ->assertSessionHasErrors();
    }
}
