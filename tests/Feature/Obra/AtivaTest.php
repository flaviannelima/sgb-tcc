<?php

namespace Tests\Feature\Obra;

use App\Obra;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtivaTest extends TestCase
{
    private function ativaObra(Obra $obra)
    {
        
        return $this->post(route('obras.ativa',['obra'=>$obra]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_e_ser_coordenador()
    {
        $obra = $this->obra()->create();

        $this->ativaObra($obra)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->ativaObra($obra)
        ->assertStatus(403);  

    }
 
    /**
     * @test
     */
    public function deve_ser_ativado_no_banco_e_redirecionar_para_pagina_index()
    {
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Obra $obra */
        $obra= $this->obra()->create();
        $obra->autores = $this->autor()->create(3);
        $obra->assuntos = $this->assunto()->create(3);
        $autores = [];
        foreach($obra->autores as $autor){
            $autores[] = $autor->id;
        }
        $obra->autores = $autores;

        $assuntos = [];
        foreach($obra->assuntos as $assunto){
            $assuntos[] = $assunto->id;
        }
        $obra->assuntos = $assuntos;
        $obra->autores()->attach($obra->autores);
        $obra->assuntos()->attach($obra->assuntos);
        $user = $this->coordenador()->create()->user()->first();
        
        $this->actingAs($user)
        ->ativaObra($obra)
        ->assertRedirect(route('obras.index'));   

        $this->assertDatabaseHas('obras',[
            'tipo_material' => $obra->tipo_material,
            'titulo' => $obra->titulo,
            'editora' => $obra->editora,
            'volume' => $obra->volume,
            'observacao' => $obra->observacao,
            'localizacao' => $obra->localizacao,
            'ativo' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * @test
     */
    public function obra_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $obra = new Obra();
        $obra->id = -1;
        $this->actingAs($user)->ativaObra($obra)->assertNotFound();
    }
}
