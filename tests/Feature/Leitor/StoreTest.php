<?php

namespace Tests\Feature\Leitor;

use App\Leitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /**
     * @property int $id
     * @property string $cpf
     * @property Carbon $data_nascimento
     * @property string $endereco
     * @property string $telefone_residencial
     * @property string $celular
     * @property int $user
     * @property Carbon $created_at
     * @property Carbon $updated_at
     */
    private function storeLeitor(Leitor $leitor)
    {


        return $this->post(route('leitores.store'), $leitor->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_ser_coordenador_ou_atendente()
    {
        $leitor = $this->leitor()->make();

        $this->storeLeitor($leitor)->assertRedirect('/login');


        $user = $this->user()->create();
        $this->actingAs($user)
            ->storeLeitor($leitor)
            ->assertStatus(403);

        $this->actingAs($this->coordenador()->create()->user()->first())->storeLeitor($leitor)
            ->assertSessionHasNoErrors();

        $leitor = $this->leitor()->make();

        $this->actingAs($this->atendente()->create()->user()->first())
            ->storeLeitor($leitor)
            ->assertSessionHasNoErrors();
    }

    /**
     * @test 
     * */
    public function deve_salvar_no_banco_e_redirecionar_para_pagina_index_users()
    {

        $leitor = $this->leitor()->make();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeLeitor($leitor)->assertRedirect(route('users.index'));
        $array = $leitor->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $array['data_nascimento'] = $leitor->data_nascimento->format('Y-m-d');
        $this->assertDatabaseHas('leitores', $array);
    }

    /**
     * @test
     */
    public function campo_cpf_deve_ser_obrigatorio()
    {
        $leitor = $this->leitor()->setCpf(null)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['cpf' => trans('validation.required', ['attribute' => 'cpf'])]
        );
    }

    /**
     * @test
     */
    public function campo_cpf_deve_ser_unico()
    {

        $leitor = $this->leitor()->setCpf($this->leitor()->create()->cpf)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['cpf' => trans('validation.unique', ['attribute' => 'cpf'])]
        );
    }

    /**
     * @test
     */
    public function campo_cpf_deve_ter_14_caracteres()
    {
        $leitor = $this->leitor()->setCpf('154')->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['cpf' => trans('validation.min.string', ['attribute' => 'cpf', 'min' => 14])]
        );
        $leitor = $this->leitor()->setCpf('154.095.886-38488848')->make();
        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['cpf' => trans('validation.max.string', ['attribute' => 'cpf', 'max' => 14])]
        );
    }

    /**
     * @test
     */
    public function campo_cpf_deve_estar_no_formato_estabelecido_e_ser_valido()
    {
        $leitor = $this->leitor()->setCpf('154-094-886.25')->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['cpf']
        );
        $leitor = $this->leitor()->setCpf('154.094.886-25')->make();
        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['cpf']
        );
    }

    /**
     * @test
     */
    public function campo_data_nascimento_obrigatorio()
    {
        $leitor = $this->leitor()->setDataNascimento(null)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['data_nascimento' => trans(
                'validation.required',
                ['attribute' => 'data de nascimento']
            )]
        );
    }

    /**
     * @test
     */
    public function campo_data_nascimento_nao_pode_ser_maior_que_hoje()
    {
        $leitor = $this->leitor()->setDataNascimento(date('Y-m-d', strtotime(date('Y-m-d') .
            ' +1 day')))->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['data_nascimento']
        );
    }

    /**
     * @test
     */
    public function campo_endereco_obrigatorio()
    {
        $leitor = $this->leitor()->setEndereco(null)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['endereco' => trans(
                'validation.required',
                ['attribute' => 'endereço']
            )]
        );
    }

    /**
     * @test
     */
    public function campo_telefone_residencial_opcional()
    {
        $leitor = $this->leitor()->setTelefoneResidencial(null)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertRedirect(route('users.index'));
        $array = $leitor->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $array['data_nascimento'] = $leitor->data_nascimento->format('Y-m-d');
        $this->assertDatabaseHas('leitores', $array);
    }

    /**
     * @test
     */
    public function campo_telefone_residencial_deve_estar_no_formato_estabelecido()
    {
        $leitor = $this->leitor()->setTelefoneResidencial('313435-8686')->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['telefone_residencial' => trans(
                'validation.regex',
                ['attribute' => 'telefone residencial']
            )]
        );
    }

    /**
     * @test
     */
    public function campo_celular_deve_ser_obrigatorio()
    {
        $leitor = $this->leitor()->setCelular(null)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['celular' => trans('validation.required', ['attribute' => 'celular'])]
        );
    }

    /**
     * @test
     */
    public function campo_celular_deve_estar_no_formato_estabelecido()
    {
        $leitor = $this->leitor()->setCelular('3193435-8686')->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['celular' => trans(
                'validation.regex',
                ['attribute' => 'celular']
            )]
        );
    }

    /**
     * @test
     */
    public function campo_user_obrigatorio()
    {
        $leitor = $this->leitor()->setUser(null)->make();
        $user = $this->coordenador()->create()->user()->first();

        $this->actingAs($user)->storeLeitor($leitor)->assertSessionHasErrors(
            ['user' => trans(
                'validation.required',
                ['attribute' => 'usuário']
            )]
        );
    }

    /**
     * @test 
     * */
    public function user_deve_estar_cadastrado_previamente()
    {
        $leitor = $this->leitor()->setUser(-1)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeLeitor($leitor)
            ->assertSessionHasErrors(['user' => trans('validation.exists', ['attribute' =>
            'usuário'])]);
    }

    /**
     * @test 
     * */
    public function user_deve_ser_unico()
    {
        $leitor = $this->leitor()->create();
        $leitorNovo = $this->leitor()->setUser($leitor->user)->make();

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)
            ->storeLeitor($leitorNovo)
            ->assertSessionHasErrors(['user' => trans('validation.unique', ['attribute' =>
            'usuário'])]);
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
            ->storeLeitor($this->leitor()->setUser($user)->make())
            ->assertSessionHasErrors();
    }
}
