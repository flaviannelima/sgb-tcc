<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AlterarSenhaTest extends TestCase
{
    private function alterarSenha($senhaAntiga, $senhaNova, $confirmaSenha)
    {


        return $this->patch(route('users.alterarSenha'),['senha_atual' => $senhaAntiga,'password' => $senhaNova, 
        'password_confirmation' => $confirmaSenha]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {

        $this->alterarSenha('teste123','teste321','teste321')->assertRedirect('/login');
     

    }
    
    /**
     * @test
     */
    public function deve_ser_salvo_no_banco()
    {
        

        $user = $this->user()->create();
        $password = 'testando';
        $user->password = Hash::make($password);
        $user->save();
        $this->actingAs($user)
        ->alterarSenha($password,'teste123','teste123')
        ->assertSessionHasNoErrors();   
        $user= User::find($user->id);
        $this->assertTrue(Hash::check('teste123',$user->password));
    }
    /**
     * @test 
     * */
    public function campo_senha_atual_obrigatorio()
    {
        $user = $this->user()->create();
        $password = 'testando';
        $user->password = Hash::make($password);
        $user->save();
        $this->actingAs($user)
        ->alterarSenha(null,'teste123','teste123')
        ->assertSessionHasErrors(['senha_atual' => trans('validation.required',['attribute' => 'senha atual'])]);
    }
    /**
     * @test 
     * */
    public function senha_atual_deve_estar_correta()
    {
        $user = $this->user()->create();
        $password = 'testando';
        $user->password = Hash::make($password);
        $user->save();
        $this->actingAs($user)
        ->alterarSenha('senhaerrada','teste123','teste123')
        ->assertSessionHasErrors();
    }
     /**
     * @test 
     * */
    public function campo_senha_nova_obrigatorio()
    {
        $user = $this->user()->create();
        $password = 'testando';
        $user->password = Hash::make($password);
        $user->save();
        $this->actingAs($user)
        ->alterarSenha($password,null,'teste123')
        ->assertSessionHasErrors(['password' => trans('validation.required',['attribute' => 'senha'])]);
    }

    
    /**
     * @test 
     * */
    public function campo_password_deve_ser_confirmado()
    {
        $user = $this->user()->create();
        $password = 'testando';
        $user->password = Hash::make($password);
        $user->save();
        $this->actingAs($user)
        ->alterarSenha($password,'teste567','teste123')
        ->assertSessionHasErrors(['password' => trans('validation.confirmed',['attribute' => 'senha'])]);
    }

    /**
     * @test 
     * */
    public function campo_password_deve_ter_no_minimo_8_caracteres()
    {
        $user = $this->user()->create();
        $password = 'testando';
        $user->password = Hash::make($password);
        $user->save();
        $this->actingAs($user)
        ->alterarSenha($password,'teste','teste')
        ->assertSessionHasErrors(['password' => trans('validation.min.string',['attribute' => 'senha',
        'min' => 8])]);
      
    }

}
