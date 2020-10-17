<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private function destroyCategoria(Categoria $categoria)
    {
        
        return $this->delete(route('categorias.destroy',['categoria'=>$categoria]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $categoria = $this->categoria()->create();

        $this->destroyCategoria($categoria)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->destroyCategoria($categoria)
        ->assertStatus(403);  

    }

    /**
     * @test
     */
    public function deve_ser_deletado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();

        $categoria= $this->categoria()->create();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->destroyCategoria($categoria)
        ->assertRedirect(route('categorias.index'));   
        $categoria->ativo = false;
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
        $categoria = new categoria();
        $categoria->id = -1;
        $this->actingAs($user)->destroyCategoria($categoria)->assertNotFound();
    }
}
