<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeCategoria(Categoria $categoria = null)
    {
        $categoria = $categoria ?? collect([]);
        return $this->post(route('categorias.store'),$categoria->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $categoria = $this->categoria()->make();
        $this->storeCategoria($categoria)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeCategoria($categoria)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        $categoria = $this->categoria()->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeCategoria($categoria)
        ->assertRedirect(route('categorias.index'));   

        $this->assertDatabaseHas('categorias',[
            'descricao' => $categoria->descricao,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_descricao_obrigatorio()
    {

        $categoria = $this->categoria()->setDescricao('')->make();
  
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->storeCategoria($categoria)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.required',['attribute' => 'descricao'])]);
    }
    /**
     * @test
     */
    public function campo_descricao_deve_ser_unico()
    {

        $this->categoria()->setDescricao('teste')->create();

        $categoria = $this->categoria()->setDescricao('teste')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeCategoria($categoria)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.unique',['attribute' => 'descricao'])]);
    }
}
