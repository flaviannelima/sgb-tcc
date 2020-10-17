<?php

namespace Tests\Feature\Exemplar;

use App\Exemplar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showExemplar(Exemplar $exemplar)
    {
        return $this->get(route('exemplares.show',$exemplar));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $exemplar = $this->exemplar()->create();
        $this->showExemplar($exemplar)->assertRedirect('/login');
    }

   
    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_exemplar()
    {
        $user = $this->user()->create();
        $exemplar = $this->exemplar()->create();
        $this->actingAs($user)->showExemplar($exemplar)->assertViewIs('exemplares.show')
        ->assertViewHas('exemplar',$exemplar);
    }

    /**
     * @test
     */
    public function exemplar_deve_existir_na_base_de_dados()
    {
        $user = $this->user()->create();
        $exemplar = new Exemplar();
        $exemplar->id = -1;
        $this->actingAs($user)->showExemplar($exemplar)->assertNotFound();
    }
}
