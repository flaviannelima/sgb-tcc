<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private function destroyAssunto(Assunto $assunto)
    {
        
        return $this->delete(route('assuntos.destroy',['assunto'=>$assunto]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $assunto = $this->assunto()->create();

        $this->destroyAssunto($assunto)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->destroyAssunto($assunto)
        ->assertStatus(403);  

    }

    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();

        $assunto= $this->assunto()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->destroyAssunto($assunto)
        ->assertRedirect(route('assuntos.index'));   
        $assunto->ativo = false;
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
        $this->actingAs($user)->destroyAssunto($assunto)->assertNotFound();
    }

    
}
