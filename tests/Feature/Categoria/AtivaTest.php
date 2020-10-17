<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaCategoria(Categoria $categoria)
    {
        
        return $this->post(route('categorias.ativa',['categoria'=>$categoria]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $categoria = $this->categoria()->create();

        $this->ativaCategoria($categoria)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaCategoria($categoria)
        ->assertStatus(403);  

    }

    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();

        $categoria= $this->categoria()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaCategoria($categoria)
        ->assertRedirect(route('categorias.index'));   
        $categoria->ativo  = true;
        $array = $categoria->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $this->assertDatabaseHas('categorias',$array);
    }

    /**
     * @test
     */
    public function categoria_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = new Categoria();
        $categoria->id = -1;
        $this->actingAs($user)->ativaCategoria($categoria)->assertNotFound();
    }
}
