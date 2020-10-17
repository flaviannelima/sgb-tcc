<?php

namespace Tests\Feature\Exemplar;

use App\Exemplar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private function updateExemplar(Exemplar $exemplar, Exemplar $exemplarNovo)
    {

        return $this->patch(route('exemplares.update', ['exemplar' => $exemplar]), $exemplarNovo->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_ser_coordenador()
    {
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->make();
        $this->updateExemplar($exemplar,$exemplarNovo)->assertRedirect('/login');


        $user = $this->user()->create();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertStatus(403);

    }


    /**
     * @test 
     * */
    public function campo_codigo_barras_obrigatorio()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setCodigoBarras(null)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['codigo_barras' => trans(
                'validation.required',
                ['attribute' => 'código de barras']
            )]);
    }

    /**
     * @test 
     * */
    public function campo_codigo_barras_deve_ser_inteiro_e_positivo()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setCodigoBarras(5.5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['codigo_barras' => trans(
                'validation.integer',
                ['attribute' => 'código de barras']
            )]);

        $exemplarNovo = $this->exemplar()->setCodigoBarras(random_int(-1000, -1))->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['codigo_barras' => trans(
                'validation.min.numeric',
                ['attribute' => 'código de barras', 'min' => 0]
            )]);
    }

    /**
     * @test 
     * */
    public function campo_codigo_barras_unico()
    {

        /**@var Exemplar $exemplar */
        $exemplarAntigo = $this->exemplar()->create();
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setCodigoBarras($exemplarAntigo->codigo_barras)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['codigo_barras' => trans(
                'validation.unique',
                ['attribute' => 'código de barras']
            )]);
    }

    /**
     * @test
     */
    public function campo_edicao_deve_ser_opcional()
    {
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setEdicao(null)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo);
        $this->assertDatabaseHas('exemplares', $exemplarNovo->toArray());
    }
    /**
     * @test 
     * */
    public function campo_edicao_deve_ser_inteiro_e_positivo()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setEdicao(5.5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['edicao' => trans(
                'validation.integer',
                ['attribute' => 'edição']
            )]);

        $exemplarNovo = $this->exemplar()->setEdicao(random_int(-1000, -1))->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['edicao' => trans(
                'validation.min.numeric',
                ['attribute' => 'edição', 'min' => 0]
            )]);
    }

    /**
     * @test
     */
    public function campo_ano_deve_ser_opcional()
    {
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setAno(null)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo);
        $this->assertDatabaseHas('exemplares', $exemplarNovo->toArray());
    }

    /**
     * @test 
     * */
    public function campo_ano_deve_ser_inteiro_e_positivo()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setAno(5.5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['ano' => trans(
                'validation.integer',
                ['attribute' => 'ano']
            )]);

        $exemplarNovo = $this->exemplar()->setAno(random_int(-1000, -1))->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['ano' => trans(
                'validation.min.numeric',
                ['attribute' => 'ano', 'min' => 0]
            )]);
    }

    /**
     * @test
     */
    public function campo_observacao_deve_ser_opcional()
    {
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setObservacao(null)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo);
        $this->assertDatabaseHas('exemplares', $exemplarNovo->toArray());
    }

    /**
     * @test 
     * */
    public function campo_obra_obrigatorio()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setObra(null)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['obra' => trans(
                'validation.required',
                ['attribute' => 'obra']
            )]);
    }

    /**
     * @test 
     * */
    public function obra_deve_estar_previamente_cadastrada()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setObra(-5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertSessionHasErrors(['obra' =>
            trans('validation.exists', ['attribute' => 'obra'])]);
    }

    /**
     * @test 
     * */
    public function deve_salvar_no_banco_e_redirecionar_para_pagina_show_obra()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->updateExemplar($exemplar,$exemplarNovo)
            ->assertRedirect(route('obras.show', $exemplarNovo->obra));
        $exemplarNovo->id = $exemplar->id;
        $this->assertDatabaseHas('exemplares', $exemplarNovo->toArray());
    }

     /**
     * @test
     */
    public function obra_deve_estar_ativa()
    {
        $user = $this->coordenador()->create()->user()->first();
        $obra = $this->obra()->create();
        $obra->ativo = 0;
        $obra->save();
        $exemplar = $this->exemplar()->setObra($obra)->create();
        $exemplarNovo = $this->exemplar()->make();
        $this->actingAs($user)->updateExemplar($exemplar,$exemplarNovo)
        ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function exemplar_deve_estar_ativo()
    {
        $user = $this->coordenador()->create()->user()->first();
        $exemplar = $this->exemplar()->create();
        $exemplar->ativo = 0;
        $exemplar->save();
        $exemplarNovo = $this->exemplar()->make();
        $this->actingAs($user)->updateExemplar($exemplar,$exemplarNovo)
        ->assertSessionHasErrors();
    }
}
