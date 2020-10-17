<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexTipoMaterial()
    {
        return $this->get(route('tiposmaterial.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexTipoMaterial()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->indexTipoMaterial()->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->coordenador()->create()->user()->first();
        $rota = $this->actingAs($user)->indexTipoMaterial();
        $tiposMaterial = TipoMaterial::orderBy('descricao')->paginate(10);
        $rota->assertViewIs('tiposmaterial.index')
        ->assertViewHas('tiposMaterial',$tiposMaterial);
    }
}
