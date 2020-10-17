<?php

namespace Tests\Feature\Emprestimo;

use App\Emprestimo;
use App\Exemplar;
use App\Leitor;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private function store(Emprestimo $emprestimo, $password = null)
    {
        $request = $emprestimo->toArray();
        if ($password == null && $emprestimo->leitor != null) {
            $password = 'testando';
            $leitor = Leitor::find($emprestimo->leitor);
            if ($leitor) {
                $user = User::find($leitor->user()->first()->id);
                $user->password=Hash::make($password);
                $user->save();
            }
        }
        
        $request['password'] = $password;
        return $this->post(route('emprestimos.store'), $request);
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->store($this->emprestimo()->make())->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_ser_atendente_ou_coordenador()
    {
        $this->actingAs($this->user()->create())->store($this->emprestimo()->make())
            ->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())
            ->store($this->emprestimo()->make())->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->make())->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function deve_ser_salvo_no_banco()
    {
        $this->withoutExceptionHandling();
        $emprestimo = $this->emprestimo()->make();
        $this->actingAs($this->atendente()->create()->user()->first())
            ->store($emprestimo)->assertSessionHasNoErrors();
        $emprestimo->exemplar = Exemplar::where('codigo_barras', $emprestimo->exemplar)->first()->id;
        $this->assertDatabaseHas('emprestimos', [
            'leitor' => $emprestimo->leitor,
            'exemplar' => $emprestimo->exemplar
        ]);
    }

    /**
     * @test
     */
    public function campo_exemplar_obrigatorio()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setExemplar(null)->make())
            ->assertSessionHasErrors('exemplar', trans('validation.required', ['attribute' => 'exemplar']));
    }

    /**
     * @test
     */
    public function exemplar_deve_estar_cadastrado_previamente()
    {
        $exemplar = new Exemplar();
        $exemplar->codigo_barras = -1;
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setExemplar($exemplar->codigo_barras)->make())
            ->assertSessionHasErrors('exemplar', trans('validation.exists', ['attribute' => 'exemplar']));
    }

    /**
     * @test
     */
    public function campo_leitor_obrigatorio()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setLeitor(null)->make())
            ->assertSessionHasErrors('leitor', trans('validation.required', ['attribute' => 'leitor']));
    }

    /**
     * @test
     */
    public function campo_senha_deve_ser_igual_a_senha_leitor()
    {
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->make(), 'teste')
            ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function leitor_deve_estar_cadastrado_previamente()
    {
        $leitor = new Leitor();
        $leitor->id = -1;
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setLeitor($leitor->id)->make())
            ->assertSessionHasErrors('leitor', trans('validation.exists', ['attribute' => 'leitor']));
    }

    /**
     * @test
     */
    public function exemplar_nao_pode_estar_emprestado()
    {
        $this->withoutExceptionHandling();
        $emprestimo = $this->emprestimo()->make();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($emprestimo);

        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setExemplar($emprestimo->exemplar)->make())->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function leitor_nao_pode_pegar_mais_de_dois_exemplares_ao_mesmo_tempo()
    {
        $this->withoutExceptionHandling();
        $emprestimo = $this->emprestimo()->make();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($emprestimo);

        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setLeitor($emprestimo->leitor)->make());

        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setLeitor($emprestimo->leitor)->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function leitor_nao_estar_com_exemplar_atrasado()
    {
        $this->withoutExceptionHandling();
        $coordenador = $this->coordenador()->create()->user()->first();
        $exemplar = $this->exemplar()->create();
        $emprestimo = $this->emprestimo()->setExemplar($exemplar->id)
            ->setDataPrevistaDevolucao(date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 days')))
            ->setUsuarioEmprestou($coordenador->id)->create();


        $this->actingAs($coordenador)
            ->store($this->emprestimo()->setLeitor($emprestimo->leitor)->make())->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function leitor_pode_pegar_dois_exemplares_ao_mesmo_tempo()
    {
        $this->withoutExceptionHandling();
        $emprestimo = $this->emprestimo()->make();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($emprestimo)->assertSessionHasNoErrors();
        $emprestimo2 = $this->emprestimo()->setLeitor($emprestimo->leitor)->make();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($emprestimo2)->assertSessionHasNoErrors();

        $emprestimo->exemplar = Exemplar::where('codigo_barras', $emprestimo->exemplar)->first()->id;
        $emprestimo2->exemplar = Exemplar::where('codigo_barras', $emprestimo2->exemplar)->first()->id;

        $this->assertDatabaseHas('emprestimos', [
            'exemplar' => $emprestimo->exemplar,
            'leitor' => $emprestimo->leitor
        ]);

        $this->assertDatabaseHas('emprestimos', [
            'exemplar' => $emprestimo2->exemplar,
            'leitor' => $emprestimo2->leitor
        ]);
    }

    /**
     * @test
     */
    public function exemplar_deve_estar_ativo()
    {
        $exemplar = $this->exemplar()->create();
        $exemplar->ativo = false;
        $exemplar->save();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setExemplar($exemplar->codigo_barras)->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function obra_deve_estar_ativa()
    {
        $obra = $this->obra()->create();
        $obra->ativo = false;
        $obra->save();
        $exemplar = $this->exemplar()->setObra($obra)->create();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setExemplar($exemplar->codigo_barras)->make())
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
            ->store($this->emprestimo()->setLeitor($leitor)->make())
            ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_leitor_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();
        $leitor = $this->leitor()->setUser($user)->create();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->store($this->emprestimo()->setLeitor($leitor)->make())
            ->assertSessionHasErrors();
    }
}
