<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeEditora(Editora $editora = null)
    {
        $editora = $editora ?? collect([]);
       
        return $this->post(route('editoras.store'),$editora->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $editora = $this->editora()->make();
        $this->storeEditora($editora)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeEditora($editora)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Editora $editora */
        $editora = $this->editora()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeEditora($editora)
        ->assertRedirect(route('editoras.index'));   

        $this->assertDatabaseHas('editoras',[
            'nome' => $editora->nome,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_nome_obrigatorio()
    {
        /**@var Editora $editora */
        $editora = $this->editora()->setNome('')->make();
  
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->storeEditora($editora)
        ->assertSessionHasErrors(['nome' => trans('validation.required',['attribute' => 'nome'])]);
    }
    /**
     * @test
     */
    public function campo_nome_deve_ser_unico()
    {

        //dd(factory(Coordenador::class)->create()->user()->first());
        $this->editora()->setNome('teste')->create();
        /**@var Editora $editora */
        $editora = $this->editora()->setNome('teste')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeEditora($editora)
        ->assertSessionHasErrors(['nome' => trans('validation.unique',['attribute' => 'nome'])]);
    }
}
