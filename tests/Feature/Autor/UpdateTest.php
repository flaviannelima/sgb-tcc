<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateAutor(Autor $autor, Autor $autorNovo)
    {
        
        return $this->patch(route('autores.update',['autor'=>$autor]),
        $autorNovo->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $autor = $this->autor()->create()->first();
        //dd($autor);
        $autorNovo = $this->autor()->make();
        $this->updateAutor($autor,$autorNovo)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->updateAutor($autor,$autorNovo)
        ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Autor $autor */
        $autorAntigo = $this->autor()->create();
        $autorNovo = $this->autor()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateAutor($autorAntigo, $autorNovo)
        ->assertRedirect(route('autores.index'));   

        $this->assertDatabaseHas('autores',[
            'nome' => $autorNovo->nome,
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_nome_obrigatorio()
    {
        /**@var Autor $autorAntigo */
        $autorAntigo = $this->autor()->create();
        /**@var Autor $autorNovo */
        $autorNovo = $this->autor()->setNome('')->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateAutor($autorAntigo,$autorNovo)
        ->assertSessionHasErrors(['nome' => trans('validation.required',['attribute' => 'nome'])]);
    }
    /**
     * @test
     */
    public function campo_nome_deve_ser_unico()
    {

        $autorAntigo = $this->autor()->setNome('teste')->create();
        $this->autor()->setNome('testenovo')->create();

        $autorNovo = $this->autor()->setNome('testenovo')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateAutor($autorAntigo,$autorNovo)
        ->assertSessionHasErrors(['nome' => trans('validation.unique',['attribute' => 'nome'])]);
    }

    /**
     * @test
     */
    public function autor_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $autor = new Autor();
        $autorNovo = $this->autor()->make();
        $autor->id = -1;
        $this->actingAs($user)->updateAutor($autor,$autorNovo)->assertNotFound();
    }

    /**
     * @test
     */
    public function autor_deve_estar_ativo()
    {
        $user = $this->coordenador()->create()->user()->first();
        $autor = $this->autor()->create();
        $autor->ativo = false;
        $autor->save();
        $autorNovo = $this->autor()->make();
        $this->actingAs($user)->updateAutor($autor,$autorNovo)->assertSessionHasErrors();
    }
}
