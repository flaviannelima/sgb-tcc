<?php

namespace Tests\Feature\Obra;

use App\Obra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexObra()
    {
        return $this->get(route('obras.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexObra()->assertRedirect('/login');
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->user()->create();
        $rota = $this->actingAs($user)->indexObra();
        $obras = Obra::orderBy('nome')->paginate(9);
        $rota->assertViewIs('obras.index')
        ->assertViewHas('obras',$obras);
    }
}
