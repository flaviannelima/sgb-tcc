<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateEditora(Editora $editora, Editora $editoraNovo)
    {
        
        return $this->patch(route('editoras.update',['editora'=>$editora]),
        $editoraNovo->toArray());
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $editora = $this->editora()->create()->first();
        //dd($editora);
        $editoraNovo = $this->editora()->make();
        $this->updateEditora($editora,$editoraNovo)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->updateEditora($editora,$editoraNovo)
        ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Editora $editora */
        $editoraAntigo = $this->editora()->create();
        $editoraNovo = $this->editora()->make();

        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateEditora($editoraAntigo, $editoraNovo)
        ->assertRedirect(route('editoras.index'));   

        $this->assertDatabaseHas('editoras',[
            'nome' => $editoraNovo->nome,
            'updated_at' => now()
        ]);
    }
    /**
     * @test 
     * */
    public function campo_nome_obrigatorio()
    {
        /**@var Editora $editoraAntigo */
        $editoraAntigo = $this->editora()->create();
        /**@var Editora $editoraNovo */
        $editoraNovo = $this->editora()->setNome('')->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
        ->updateEditora($editoraAntigo,$editoraNovo)
        ->assertSessionHasErrors(['nome' => trans('validation.required',['attribute' => 'nome'])]);
    }
    /**
     * @test
     */
    public function campo_nome_deve_ser_unico()
    {

        $editoraAntigo = $this->editora()->setNome('teste')->create();
        $this->editora()->setNome('testenovo')->create();

        $editoraNovo = $this->editora()->setNome('testenovo')->make();
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->updateEditora($editoraAntigo,$editoraNovo)
        ->assertSessionHasErrors(['nome' => trans('validation.unique',['attribute' => 'nome'])]);
    }

    /**
     * @test
     */
    public function editora_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = new Editora();
        $editoraNovo = $this->editora()->make();
        $editora->id = -1;
        $this->actingAs($user)->updateEditora($editora,$editoraNovo)->assertNotFound();
    }

    /**
     * @test
     */
    public function editora_deve_estar_ativa()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = $this->editora()->create();
        $editora->ativo = false;
        $editora->save();
        $editoraNovo = $this->editora()->make();
        $this->actingAs($user)->updateEditora($editora,$editoraNovo)->assertSessionHasErrors();
    }
}
