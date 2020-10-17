<?php

namespace Tests\Feature\Emprestimo;

use App\Emprestimo;
use App\Exemplar;
use App\Leitor;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DevolucaoTest extends TestCase
{
    private function devolucao(Emprestimo $emprestimo)
    {
        return $this->get(route('emprestimos.devolucao',['emprestimo'=>$emprestimo]));
    }

    private function store(Emprestimo $emprestimo)
    {
        $request = $emprestimo->toArray();
       
            $password = 'testando';
            $leitor = Leitor::find($emprestimo->leitor);
            if ($leitor) {
                $user = User::find($leitor->user()->first()->id);
                $user->password=Hash::make($password);
                $user->save();
            }
        
        
        $request['password'] = $password;
        return $this->post(route('emprestimos.store'), $request);
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->actingAs($this->atendente()->create()->user()->first())->store($this->emprestimo()->make());
        $emprestimo = Emprestimo::first();
        auth()->logout();
        $this->devolucao($emprestimo)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_ser_atendente_ou_coordenador()
    {
        //$this->withoutExceptionHandling();
        $this->actingAs($this->atendente()->create()->user()->first())->store($this->emprestimo()->make());
        $emprestimo = Emprestimo::first();
      
        $this->actingAs($this->user()->create())->devolucao($emprestimo)
            ->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())
            ->devolucao($emprestimo)->assertSessionHasNoErrors();

        $e = $this->emprestimo()->make();
        $this->actingAs($this->atendente()->create()->user()->first())->store($e);
        $emprestimo = Emprestimo::orderBy('id','desc')->first();
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->devolucao($emprestimo)->assertSessionHasNoErrors();
        
    }

     /**
     * @test
     */
    public function deve_ser_salvo_no_banco()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->atendente()->create()->user()->first())->store($this->emprestimo()->make());
        $emprestimo = Emprestimo::first();
        $this->actingAs($this->atendente()->create()->user()->first())
            ->devolucao($emprestimo)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('emprestimos', [
            'leitor' => $emprestimo->leitor,
            'exemplar' => $emprestimo->exemplar,
            'data_devolucao' => date('Y-m-d')
        ]);
    }

    /**
     * @test
     */
    public function emprestimo_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $emprestimo = new Emprestimo();
        $emprestimo->id = -1;
        $this->actingAs($user)->devolucao($emprestimo)->assertNotFound();
    }

    /**
     * @test
     */
    public function emprestimo_deve_ter_a_data_devolucao_nula()
    {
        $this->withoutExceptionHandling();
        Carbon::setTestNow(now());
        $this->actingAs($this->atendente()->create()->user()->first())->store($this->emprestimo()->make());
        $emprestimo = Emprestimo::first();
        $emprestimo->data_devolucao ='2020-05-05';
        $emprestimo->save();
        $user = $this->coordenador()->create()->user()->first();;
        $this->actingAs($user)->devolucao($emprestimo)->assertSessionHasErrors();
        $array = $emprestimo->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);
        $array['data_prevista_devolucao'] = $emprestimo->data_prevista_devolucao->format('Y-m-d');
        $array['data_devolucao'] = $emprestimo->data_devolucao->format('Y-m-d');
        $this->assertDatabaseHas('emprestimos',$array);
    }
}
