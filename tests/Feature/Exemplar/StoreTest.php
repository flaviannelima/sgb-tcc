<?php

namespace Tests\Feature\Exemplar;

use App\Exemplar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{

    private function storeExemplar(Exemplar $exemplar = null)
    {
        $exemplar = $exemplar ?? collect([]);
        return $this->post(route('exemplares.store'), $exemplar->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_ser_coordenador()
    {
        $exemplar = $this->exemplar()->make();

        $this->storeExemplar($exemplar)->assertRedirect('/login');


        $user = $this->user()->create();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
            ->assertStatus(403);
    }


    /**
     * @test 
     * */
    public function campo_codigo_barras_obrigatorio()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->setCodigoBarras(null)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
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
        $exemplar = $this->exemplar()->setCodigoBarras(5.5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
            ->assertSessionHasErrors(['codigo_barras' => trans(
                'validation.integer',
                ['attribute' => 'código de barras']
            )]);

        $exemplar = $this->exemplar()->setCodigoBarras(random_int(-1000, -1))->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
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
        $exemplar = $this->exemplar()->create();
        $exemplarNovo = $this->exemplar()->setCodigoBarras($exemplar->codigo_barras)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplarNovo)
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
        $exemplar = $this->exemplar()->setEdicao(null)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar);
        $this->assertDatabaseHas('exemplares', $exemplar->toArray());
    }
    /**
     * @test 
     * */
    public function campo_edicao_deve_ser_inteiro_e_positivo()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->setEdicao(5.5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
            ->assertSessionHasErrors(['edicao' => trans(
                'validation.integer',
                ['attribute' => 'edição']
            )]);

        $exemplar = $this->exemplar()->setEdicao(random_int(-1000, -1))->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
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
        $exemplar = $this->exemplar()->setAno(null)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar);
        $this->assertDatabaseHas('exemplares', $exemplar->toArray());
    }

    /**
     * @test 
     * */
    public function campo_ano_deve_ser_inteiro_e_positivo()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->setAno(5.5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
            ->assertSessionHasErrors(['ano' => trans(
                'validation.integer',
                ['attribute' => 'ano']
            )]);

        $exemplar = $this->exemplar()->setAno(random_int(-1000, -1))->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
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
        $exemplar = $this->exemplar()->setObservacao(null)->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar);
        $this->assertDatabaseHas('exemplares', $exemplar->toArray());
    }

    /**
     * @test 
     * */
    public function campo_obra_obrigatorio()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->setObra(null)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
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
        $exemplar = $this->exemplar()->setObra(-5)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)
            ->assertSessionHasErrors(['obra' =>
            trans('validation.exists', ['attribute' => 'obra'])]);
    }

    /**
     * @test 
     * */
    public function deve_salvar_no_banco_e_redirecionar_para_pagina_show_obra()
    {

        /**@var Exemplar $exemplar */
        $exemplar = $this->exemplar()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeExemplar($exemplar)->assertRedirect(route('obras.show', $exemplar->obra));

        $this->assertDatabaseHas('exemplares', $exemplar->toArray());
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
        $this->actingAs($user)->storeExemplar($exemplar)
            ->assertSessionHasErrors();
    }
}
