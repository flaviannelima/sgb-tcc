<?php

namespace Tests\Feature\Leitor;

use App\Leitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function edit(Leitor $leitor){
        return $this->get(route('leitores.edit',['leitor' => $leitor]));
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->edit($this->leitor()->create())->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_ser_coordenador_ou_atendente()
    {
        $this->actingAs($this->user()->create())->edit($this->leitor()->create())
        ->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())
        ->edit($this->leitor()->create())
        ->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())
        ->edit($this->leitor()->create())
        ->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_leitor()
    {
        $user = $this->coordenador()->create()->user()->first();
        $leitor = $this->leitor()->create();
        $this->actingAs($user)->edit($leitor)->assertViewIs('leitores.edit')
        ->assertViewHas('leitor',$leitor);
    }

    /**
     * @test
     */
    public function leitor_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $leitor = new Leitor();
        $leitor->id = -1;
        $this->actingAs($user)->edit($leitor)->assertNotFound();
    }

    /**
     * @test
     */
    public function user_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->edit($this->leitor()->setUser($user)->create())
            ->assertSessionHasErrors();
    }

     /**
     * @test
     */
    public function leitor_deve_estar_ativo()
    {
        $leitor = $this->leitor()->create();
        $leitor->ativo = false;
        $leitor->save();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->edit($leitor)
            ->assertSessionHasErrors();
    }
}
