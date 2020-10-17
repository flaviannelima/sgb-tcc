<?php

namespace Tests\Feature\Atendente;

use App\Atendente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeAtendente(Atendente $atendente)
    {

        
        return $this->post(route('atendentes.store'), $atendente->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_ser_coordenador()
    {
        $atendente = $this->atendente()->make();

        $this->storeAtendente($atendente)->assertRedirect('/login');


        $user = $this->user()->create();
        $this->actingAs($user)
            ->storeAtendente($atendente)
            ->assertStatus(403);
    }


    /**
     * @test 
     * */
    public function campo_user_obrigatorio()
    {
        
        $atendente = $this->atendente()->setUser(null)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeAtendente($atendente)
            ->assertSessionHasErrors(['user' => trans('validation.required', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function user_deve_estar_cadastrado_previamente()
    {
        $atendente = $this->atendente()->setUser(-1)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeAtendente($atendente)
            ->assertSessionHasErrors(['user' => trans('validation.exists', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function user_deve_ser_unico()
    {
        $atendente = $this->atendente()->create();
        $atendenteNovo = $this->atendente()->setUser($atendente->user)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeAtendente($atendenteNovo)
            ->assertSessionHasErrors(['user' => trans('validation.unique', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function deve_salvar_no_banco_e_redirecionar_para_pagina_index()
    {
        $this->withoutExceptionHandling();
        $atendente = $this->atendente()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeAtendente($atendente)->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('atendentes', $atendente->toArray());
    }

    /**
     * @test
     */
    public function user_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();
        $atendente = $this->atendente()->setUser($user)->make();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->storeAtendente($atendente)
            ->assertSessionHasErrors();
    }
}
