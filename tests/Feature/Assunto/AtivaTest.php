<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaAssunto(Assunto $assunto)
    {
        
        return $this->post(route('assuntos.ativa',['assunto'=>$assunto]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $assunto = $this->assunto()->create();

        $this->ativaAssunto($assunto)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaAssunto($assunto)
        ->assertStatus(403);  

    }

    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();

        $assunto= $this->assunto()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaAssunto($assunto)
        ->assertRedirect(route('assuntos.index'));   
        $assunto->ativo  = true;
        $array = $assunto->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('assuntos',$array);
    }

    /**
     * @test
     */
    public function assunto_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $assunto = new Assunto();
        $assunto->id = -1;
        $this->actingAs($user)->ativaAssunto($assunto)->assertNotFound();
    }
}
