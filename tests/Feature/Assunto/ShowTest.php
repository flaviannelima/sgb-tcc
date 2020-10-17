<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showAssunto(Assunto $assunto)
    {
        return $this->get(route('assuntos.show',$assunto));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $assunto = $this->assunto()->create();
        $this->showAssunto($assunto)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $assunto = $this->assunto()->create();
        $this->actingAs($user)->showAssunto($assunto)->assertStatus(403);
    }

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_assunto()
    {
        $user = $this->coordenador()->create()->user()->first();
        $assunto = $this->assunto()->create();
        $this->actingAs($user)->showAssunto($assunto)->assertViewIs('assuntos.show')
        ->assertViewHas('assunto',$assunto);
    }

    /**
     * @test
     */
    public function assunto_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $assunto = new Assunto();
        $assunto->id = -1;
        $this->actingAs($user)->showAssunto($assunto)->assertNotFound();
    }
}
