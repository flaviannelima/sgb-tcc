<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function storeAssunto(Assunto $assunto = null)
    {
        $assunto = $assunto ?? collect([]);
        return $this->post(route('assuntos.store'),$assunto->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $assunto = $this->assunto()->make();
        $this->storeAssunto($assunto)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeAssunto($assunto)
        ->assertStatus(403);  

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redireciona_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        $assunto = $this->assunto()->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeAssunto($assunto)
        ->assertRedirect(route('assuntos.index'));   

        $this->assertDatabaseHas('assuntos',[
            'descricao' => $assunto->descricao,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_descricao_obrigatorio()
    {

        $assunto = $this->assunto()->setDescricao('')->make();
  
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->storeAssunto($assunto)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.required',['attribute' => 'descricao'])]);
    }
    /**
     * @test
     */
    public function campo_descricao_deve_ser_unico()
    {

        $this->assunto()->setDescricao('teste')->create();

        $assunto = $this->assunto()->setDescricao('teste')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->storeAssunto($assunto)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.unique',['attribute' => 'descricao'])]);
    }
}
