<?php

namespace Tests\Feature\Obra;

use App\Obra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaObra(Obra $obra)
    {
        return $this->post(route('obras.busca'),["obra"=>$obra->toArray()]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $obra = $this->obra()->create();
        $this->buscaObra($obra)->assertRedirect('/login');
    }

    
    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_busca()
    {
        $this->withoutExceptionHandling();
        $user = $this->user()->create();
        $obra = $this->obra()->create();
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
 
        $rota = $this->actingAs($user)->buscaObra($obra);
        $pesquisa = Obra::orderBy('titulo')->paginate(9);
        $rota->assertViewIs('obras.index')
        ->assertViewHas('obras',$pesquisa);
    }
}
