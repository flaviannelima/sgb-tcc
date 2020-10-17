<?php

namespace Tests\Feature\Leitor;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Leitor;

class UpdateTest extends TestCase
{
    private function update(Leitor $leitorAntigo, Leitor $leitorNovo)
    {
        return $this->patch(
            route('leitores.update', ['leitor' => $leitorAntigo]),
            $leitorNovo->toArray()
        );
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->update($this->leitor()->create(), $this->leitor()->make())
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_ser_coordenador_ou_atendente()
    {
        $this->actingAs($this->user()->create())
            ->update($this->leitor()->create(), $this->leitor()->make())->assertStatus(403);

        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->make())->assertSessionHasNoErrors();

        $this->actingAs($this->leitor()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->make())->assertSessionHasNoErrors();
    }

    /**
     * @test
     */

    public function leitor_deve_ser_alterado_no_banco_e_redirecionar_para_pagina_indes()
    {
        $leitorAntigo = $this->leitor()->create();
        $leitorNovo = $this->leitor()->make();

        $this->actingAs($this->atendente()->create()->user()->first())
            ->update($leitorAntigo, $leitorNovo)->assertRedirect(route('users.index'));
        $array = $leitorNovo->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $array['data_nascimento'] = $leitorNovo->data_nascimento->format('Y-m-d');
        $this->assertDatabaseHas('leitores', $array);

        $array = $leitorAntigo->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $array['data_nascimento'] = $leitorAntigo->data_nascimento->format('Y-m-d');
        $this->assertDatabaseMissing('leitores', $array);
    }

    /**
     * @test
     */
    public function campo_cpf_deve_ser_obrigatorio()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setCpf(null)->make())
            ->assertSessionHasErrors(['cpf' => trans('validation.required', ['attribute' => 'cpf'])]);
    }

    /**
     * @test
     */

    public function campo_cpf_deve_ser_unico()
    {
        $this->actingAs($this->atendente()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setCpf($this->leitor()->create()->cpf)->make()
            )
            ->assertSessionHasErrors(['cpf' => trans('validation.unique', ['attribute' => 'cpf'])]);
    }

    /**
     * @test
     */
    public function campo_cpf_deve_estar_em_um_formato_predefinido()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setCpf('949-113.441-80')->make())
            ->assertSessionHasErrors(['cpf']);
    }

    /**
     * @test
     */
    public function cpf_deve_ser_valido()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setCpf('555.094.555-22')->make())
            ->assertSessionHasErrors(['cpf']);
    }

    /**
     * @test
     */
    public function campo_data_nascimento_obrigatorio()
    {
        $this->actingAs($this->atendente()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setDataNascimento(null)->make())
            ->assertSessionHasErrors(['data_nascimento' => trans(
                'validation.required',
                ['attribute' => 'data de nascimento']
            )]);
    }

    /**
     * @test
     */
    public function campo_data_nascimento_tem_que_ser_menor_que_hoje()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setDataNascimento(date('Y-m-d'))->make()
            )
            ->assertSessionHasErrors(['data_nascimento']);
    }

    /**
     * @test
     */
    public function campo_endereco_obrigatorio()
    {
        $this->actingAs($this->atendente()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setEndereco(null)->make())
            ->assertSessionHasErrors(['endereco' => trans('validation.required', ['attribute' =>
            'endereço'])]);
    }

    /**
     * @test
     */
    public function campo_telefone_residencial_opcional()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setTelefoneResidencial(null)->make()
            )
            ->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function campo_telefone_residencial_deve_ter_um_formato_predefinido()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setTelefoneResidencial('313334-4626')->make()
            )
            ->assertSessionHasErrors(['telefone_residencial' => trans('validation.regex', [
                'attribute' => 'telefone residencial'
            ])]);
    }

    /**
     * @test
     */
    public function campo_celular_obrigatorio()
    {
        $this->actingAs($this->atendente()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setCelular(null)->make()
            )
            ->assertSessionHasErrors(['celular' => trans('validation.required', ['attribute'
            => 'celular'])]);
    }

    /**
     * @test
     */
    public function campo_celular_deve_ter_um_formato_predefinido()
    {
        $this->actingAs($this->atendente()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setCelular('3193334-4626')->make()
            )
            ->assertSessionHasErrors(['celular' => trans('validation.regex', [
                'attribute' => 'celular'
            ])]);
    }

    /**
     * @test
     */
    public function campo_user_obrigatorio()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setUser(null)->make())
            ->assertSessionHasErrors(['user' => trans(
                'validation.required',
                ['attribute' => 'usuário']
            )]);
    }

    /**
     * @test
     */
    public function user_deve_estar_cadastrado_no_banco()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->create(), $this->leitor()->setUser(-1)->make())
            ->assertSessionHasErrors(['user' => trans(
                'validation.exists',
                ['attribute' => 'usuário']
            )]);
    }

    /**
     * @test
     */
    public function campo_user_deve_ser_unico()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update(
                $this->leitor()->create(),
                $this->leitor()->setUser($this->leitor()->create()->user()->first()->id)->make()
            )
            ->assertSessionHasErrors(['user' => trans(
                'validation.unique',
                ['attribute' => 'usuário']
            )]);
    }
    /**
     * @test
     */
    public function user_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();


        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($this->leitor()->setUser($user)->create(), $this->leitor()->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function leitor_deve_estar_ativo()
    {
        $leitor = $this->leitor()->create();
        $leitor->ativo = false;
        $leitor->save();


        $this->actingAs($this->coordenador()->create()->user()->first())
            ->update($leitor, $this->leitor()->make())
            ->assertSessionHasErrors();
    }
}
