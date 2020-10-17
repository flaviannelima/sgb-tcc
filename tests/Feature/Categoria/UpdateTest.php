<?php

namespace Tests\Feature\Categoria;

use App\Categoria;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateCategoria(Categoria $categoria, Categoria $categoriaNovo)
    {
        
        return $this->patch(route('categorias.update',['categoria'=>$categoria]),
        $categoriaNovo->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $categoria = $this->categoria()->create()->first();
        $categoriaNovo = $this->categoria()->make();
        $this->updateCategoria($categoria,$categoriaNovo)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->updateCategoria($categoria,$categoriaNovo)
        ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        $categoria = $this->categoria()->create();
        $categoriaNovo = $this->categoria()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateCategoria($categoria, $categoriaNovo)
        ->assertRedirect(route('categorias.index'));   

        $this->assertDatabaseHas('categorias',[
            'descricao' => $categoriaNovo->descricao,
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_descricao_obrigatorio()
    {
        $categoria = $this->categoria()->create();
        $categoriaNovo = $this->categoria()->setDescricao('')->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateCategoria($categoria,$categoriaNovo)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.required',['attribute' => 'descricao'])]);
    }
    /**
     * @test
     */
    public function campo_descricao_deve_ser_unico()
    {

        $categoria = $this->categoria()->setDescricao('teste')->create();
        $this->categoria()->setDescricao('testenovo')->create();

        $categoriaNovo= $this->categoria()->setDescricao('testenovo')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateCategoria($categoria,$categoriaNovo)
        ->assertSessionHasErrors(['descricao' => 
        trans('validation.unique',['attribute' => 'descricao'])]);
    }

    /**
     * @test
     */
    public function categoria_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = new Categoria();
        $categoriaNovo = $this->categoria()->make();
        $categoria->id = -1;
        $this->actingAs($user)->updateCategoria($categoria,$categoriaNovo)
        ->assertNotFound();
    }

    /**
     * @test
     */
    public function categoria_deve_estar_ativo()
    {
        $user = $this->coordenador()->create()->user()->first();
        $categoria = $this->categoria()->create();
        $categoriaNovo = $this->categoria()->make();
        $categoria->ativo = false;
        $categoria->save();
        $this->actingAs($user)->updateCategoria($categoria,$categoriaNovo)
        ->assertSessionHasErrors();
    }
}
