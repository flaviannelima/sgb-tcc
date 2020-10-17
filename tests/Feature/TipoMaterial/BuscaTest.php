<?php

namespace Tests\Feature\TipoMaterial;

use App\TipoMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaTipoMaterial(TipoMaterial $tipoMaterial)
    {
        return $this->post(route('tiposmaterial.busca'),["descricao"=>$tipoMaterial->descricao]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $tipoMaterial = $this->tipoMaterial()->create();
        $this->buscaTipoMaterial($tipoMaterial)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $tipoMaterial = $this->tipoMaterial()->create();
        $this->actingAs($user)->buscaTipoMaterial($tipoMaterial)->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_buscar_tipos_de_material()
    {
        $user = $this->coordenador()->create()->user()->first();
        //$this->withoutExceptionHandling();
        $tipoMaterial = $this->tipoMaterial()->create();
        $rota = $this->actingAs($user)->buscaTipoMaterial($tipoMaterial);
        $tiposMaterial = TipoMaterial::where('descricao','LIKE','%'.$tipoMaterial->descricao.'%')
        ->orderBy('descricao')->paginate(10);
        $rota->assertViewIs('tiposmaterial.index')
        ->assertViewHas('tiposMaterial',$tiposMaterial);
    }

}
