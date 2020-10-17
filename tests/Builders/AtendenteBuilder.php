<?php

namespace Tests\Builders;

use App\Atendente;

class AtendenteBuilder{
    protected $atributos = [];
    
    public function setUser($user)
    {
        $this->atributos['user'] = $user;
        return $this;
    }
    public function create($quantidade = null)
    {
        return factory(Atendente::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        
        return factory(Atendente::class,$quantidade)->make($this->atributos);
    }
}