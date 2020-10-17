<?php

namespace Tests\Feature\Autor;

use App\Autor;
use App\Coordenador;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Builders\AutorBuilder;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeAutor(Autor $autor = null)
    {
        $autor = $autor ?? collect([]);
        return $this->post(route('autores.store'),$autor->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $autor = $this->autor()->make();
        $this->storeAutor($autor)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeAutor($autor)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Autor $autor */
        $autor = $this->autor()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeAutor($autor)
        ->assertRedirect(route('autores.index'));   

        $this->assertDatabaseHas('autores',[
            'nome' => $autor->nome,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_nome_obrigatorio()
    {
        /**@var Autor $autor */
        $autor = $this->autor()->setNome('')->make();
  
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->storeAutor($autor)
        ->assertSessionHasErrors(['nome' => trans('validation.required',['attribute' => 'nome'])]);
    }
    /**
     * @test
     */
    public function campo_nome_deve_ser_unico()
    {

        //dd(factory(Coordenador::class)->create()->user()->first());
        $this->autor()->setNome('teste')->create();
        /**@var Autor $autor */
        $autor = $this->autor()->setNome('teste')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeAutor($autor)
        ->assertSessionHasErrors(['nome' => trans('validation.unique',['attribute' => 'nome'])]);
    }
   
}
