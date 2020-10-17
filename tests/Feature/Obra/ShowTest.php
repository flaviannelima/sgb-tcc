<?php

namespace Tests\Feature\Obra;

use App\Obra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showObra(Obra $obra)
    {
        return $this->get(route('obras.show',$obra));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $obra = $this->obra()->create();
        $this->showObra($obra)->assertRedirect('/login');
    }

   
    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_obra()
    {
        $user = $this->user()->create();
        $obra = $this->obra()->create();
        $this->actingAs($user)->showObra($obra)->assertViewIs('obras.show')
        ->assertViewHas('obra',$obra);
    }

    /**
     * @test
     */
    public function obra_deve_existir_na_base_de_dados()
    {
        $user = $this->user()->create();
        $obra = new Obra();
        $obra->id = -1;
        $this->actingAs($user)->showObra($obra)->assertNotFound();
    }
}
