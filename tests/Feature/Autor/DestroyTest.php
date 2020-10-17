<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private function destroyAutor(Autor $autor)
    {
        
        return $this->delete(route('autores.destroy',['autor'=>$autor]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $autor = $this->autor()->create();

        $this->destroyAutor($autor)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->destroyAutor($autor)
        ->assertStatus(403);  

    }
  
    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Autor $autor */
        $autor= $this->autor()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->destroyAutor($autor)
        ->assertRedirect(route('autores.index'));   

        $autor->ativo = false;
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
        $this->actingAs($user)->destroyAutor($autor)->assertNotFound();
    }

  
    
}
