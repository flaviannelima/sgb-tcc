<?php

namespace Tests\Feature\Renovacao;

use App\Emprestimo;
use App\Renovacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function store(Renovacao $renovacao)
    {
        return $this->post(route('renovacoes.store'), $renovacao->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->store($this->renovacao()->make())->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_ser_atendente_ou_coordenador()
    {
        $this->actingAs($this->user()->create())->store($this->renovacao()->make())
            ->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())
            ->store($this->renovacao()->make())->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->renovacao()->make())->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function deve_ser_salvo_no_banco()
    {
        $this->withoutExceptionHandling();

        $atendente = $this->atendente()->create()->user()->first();
        $emprestimo = $this->emprestimo()->setExemplar($this->exemplar()->create())
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 6 days')))
            ->setUsuarioEmprestou($atendente->id)->create();
        $renovacao = $this->renovacao()->setEmprestimo($emprestimo)->make();
        $this->actingAs($atendente)
            ->store($renovacao)->assertSessionHasNoErrors();

        $this->assertDatabaseHas('renovacoes', $renovacao->toArray());
    }

    /**
     * @test
     */
    public function data_prevista_devolucao_nao_pode_ser_maior_que_a_data_prevista_devolucao_da_renovacao()
    {
        $this->withoutExceptionHandling();

        $atendente = $this->atendente()->create()->user()->first();
        $emprestimo = $this->emprestimo()->setExemplar($this->exemplar()->create())
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 14 days')))
            ->setUsuarioEmprestou($atendente->id)->create();
        $renovacao = $this->renovacao()->setEmprestimo($emprestimo)->make();
        $this->actingAs($atendente)
            ->store($renovacao)->assertSessionHasErrors();

        $emprestimo = $this->emprestimo()->setExemplar($this->exemplar()->create())
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 days')))
            ->setUsuarioEmprestou($atendente->id)->create();
        $this->renovacao()->setEmprestimo($emprestimo)->setUsuarioRenovou($atendente->id)
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days')))->create();
        $renovacao = $this->renovacao()->setEmprestimo($emprestimo)->make();
        $this->actingAs($atendente)
            ->store($renovacao)->assertSessionHasErrors();

        $emprestimo = $this->emprestimo()->setExemplar($this->exemplar()->create())
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 days')))
            ->setUsuarioEmprestou($atendente->id)->create();
        $this->renovacao()->setEmprestimo($emprestimo)->setUsuarioRenovou($atendente->id)
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 6 days')))->create();
        $renovacao = $this->renovacao()->setEmprestimo($emprestimo)->make();
        $this->actingAs($atendente)
            ->store($renovacao)->assertSessionHasNoErrors();

        $this->assertDatabaseHas('renovacoes', $renovacao->toArray());
    }

    /**
     * @test 
     * */
    public function obra_deve_estar_ativo()
    {
        $this->withoutExceptionHandling();
        $obra = $this->obra()->create();
        $obra->ativo = false;
        $obra->save();

        $exemplar = $this->exemplar()->setObra($obra)->create();
        $user = $this->coordenador()->create()->user()->first();
        $emprestimo = $this->emprestimo()->setExemplar($exemplar)->setUsuarioEmprestou($user)
        ->setDataPrevistaDevolucao(date('Y-m-d'))->create();


        $this->actingAs($user)
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())
            ->assertSessionHasErrors();
    }
    /**
     * @test 
     * */
    public function exemplar_deve_estar_ativo()
    {

        $exemplar = $this->exemplar()->create();
        $exemplar->ativo = false;
        $exemplar->save();
        $user = $this->coordenador()->create()->user()->first();
        $emprestimo = $this->emprestimo()->setExemplar($exemplar)->setUsuarioEmprestou($user)
        ->setDataPrevistaDevolucao(date('Y-m-d'))->create();


        $this->actingAs($user)
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test 
     * */
    public function user_leitor_deve_estar_ativo()
    {
        $this->withoutExceptionHandling();
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();
        $exemplar = $this->exemplar()->create();

        $leitor = $this->leitor()->setUser($user)->create();
        $user = $this->coordenador()->create()->user()->first();
        $emprestimo = $this->emprestimo()->setLeitor($leitor)->setUsuarioEmprestou($user)
        ->setDataPrevistaDevolucao(date('Y-m-d'))->setExemplar($exemplar)->create();


        $this->actingAs($user)
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test 
     * */
    public function leitor_deve_estar_ativo()
    {

        $leitor = $this->leitor()->create();
        $leitor->ativo = false;
        $leitor->save();
        $exemplar = $this->exemplar()->create();
        $user = $this->coordenador()->create()->user()->first();
        $emprestimo = $this->emprestimo()->setExemplar($exemplar)->setUsuarioEmprestou($user)->setLeitor($leitor)
        ->setDataPrevistaDevolucao(date('Y-m-d'))->create();


        $this->actingAs($user)
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function emprestimo_deve_estar_previamente_no_banco()
    {
        $emprestimo = new Emprestimo();
        $emprestimo->id = -1;
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())
            ->assertSessionHasErrors('emprestimo', trans('validation.exists', ['attribute' => 'emprestimo']));
    }

    /**
     * @test
     */
    public function emprestimo_deve_ser_obrigatorio()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->renovacao()->setEmprestimo(null)->make())
            ->assertSessionHasErrors('emprestimo', trans('validation.required', ['attribute' => 'emprestimo']));
    }

    /**
     * @test
     */
    public function leitor_nao_pode_estar_com_exemplar_atrasado()
    {
        $this->withoutExceptionHandling();
        $coordenador = $this->coordenador()->create()->user()->first();
        $exemplar = $this->exemplar()->create();
        $emprestimo = $this->emprestimo()->setExemplar($exemplar->id)
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 days')))
            ->setUsuarioEmprestou($coordenador->id)->create();

        $emprestimo = $this->emprestimo()->setExemplar($this->exemplar()->create())->setLeitor($emprestimo->leitor)
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days')))
            ->setUsuarioEmprestou($coordenador->id)->create();


        $this->actingAs($coordenador)
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function data_devolucao_tem_que_ser_nula()
    {
        $this->withoutExceptionHandling();
        $coordenador = $this->coordenador()->create()->user()->first();
        $exemplar = $this->exemplar()->create();
        $this->emprestimo()->setExemplar($exemplar->id)
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' + 14 days')))
            ->setUsuarioEmprestou($coordenador->id)->create();
        $emprestimo = Emprestimo::first();
        $emprestimo->data_devolucao = '2020-05-05';
        $emprestimo->save();


        $this->actingAs($coordenador)
            ->store($this->renovacao()->setEmprestimo($emprestimo)->make())->assertSessionHasErrors();
    }
}
