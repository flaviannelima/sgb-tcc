<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaAutor(Autor $autor)
    {
        
        return $this->post(route('autores.ativa',['autor'=>$autor]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $autor = $this->autor()->create();

        $this->ativaAutor($autor)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaAutor($autor)
        ->assertStatus(403);  

    }
  
    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Autor $autor */
        $autor= $this->autor()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaAutor($autor)
        ->assertRedirect(route('autores.index'));   

        $autor->ativo = true;
        $array = $autor->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('autores',$array);
    }

    /**
     * @test
     */
    public function autor_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $autor = new Autor();
        $autor->id = -1;
        $this->actingAs($user)->ativaAutor($autor)->assertNotFound();
    }
}
