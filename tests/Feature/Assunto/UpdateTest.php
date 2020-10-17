<?php

namespace Tests\Feature\Assunto;

use App\Assunto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateAssunto(Assunto $assunto, Assunto $assuntoNovo)
    {
        
        return $this->patch(route('assuntos.update',['assunto'=>$assunto]),
        $assuntoNovo->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $assunto = $this->assunto()->create()->first();
        $assuntoNovo = $this->assunto()->make();
        $this->updateAssunto($assunto,$assuntoNovo)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->updateAssunto($assunto,$assuntoNovo)
        ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        $assunto = $this->assunto()->create();
        $assuntoNovo = $this->assunto()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateAssunto($assunto, $assuntoNovo)
        ->assertRedirect(route('assuntos.index'));   

        $this->assertDatabaseHas('assuntos',[
            'descricao' => $assuntoNovo->descricao,
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_descricao_obrigatorio()
    {
        $assunto = $this->assunto()->create();
        $assuntoNovo = $this->assunto()->setDescricao('')->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateAssunto($assunto,$assuntoNovo)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.required',['attribute' => 'descricao'])]);
    }
    /**
     * @test
     */
    public function campo_descricao_deve_ser_unico()
    {

        $assunto = $this->assunto()->setDescricao('teste')->create();
        $this->assunto()->setDescricao('testenovo')->create();

        $assuntoNovo= $this->assunto()->setDescricao('testenovo')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateAssunto($assunto,$assuntoNovo)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.unique',['attribute' => 'descricao'])]);
    }

    /**
     * @test
     */
    public function assunto_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $assunto = new Assunto();
        $assuntoNovo = $this->assunto()->make();
        $assunto->id = -1;
        $this->actingAs($user)->updateAssunto($assunto,$assuntoNovo)
        ->assertNotFound();
    }

    /**
     * @test
     */
    public function assunto_deve_estar_ativo()
    {
        $user = $this->coordenador()->create()->user()->first();
        $assunto = $this->assunto()->create();
        $assuntoNovo = $this->assunto()->make();
        $assunto->ativo = false;
        $assunto->save();
        $this->actingAs($user)->updateAssunto($assunto,$assuntoNovo)
        ->assertSessionHasErrors();
    }
}
