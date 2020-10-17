<?php

namespace Tests\Feature\Leitor;

use App\Leitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function show(Leitor $leitor){
        return $this->get(route('leitores.show',['leitor' => $leitor]));
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->show($this->leitor()->create())->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_ser_atendente_ou_coordenador()
    {
        $this->actingAs($this->user()->create())->show($this->leitor()->create())
        ->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())
        ->show($this->leitor()->create())
        ->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())
        ->show($this->leitor()->create())
        ->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function leitor_deve_estar_na_base_de_dados()
    {
        $leitor = new Leitor();
        $leitor->id= -1;
        $this->actingAs($this->atendente()->create()->user()->first())
        ->show($leitor)
        ->assertNotFound();
    }
}
