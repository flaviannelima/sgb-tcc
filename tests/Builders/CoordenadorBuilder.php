<?php

namespace Tests\Builders;

use App\Coordenador;
use App\User;

class CoordenadorBuilder{
    protected $atributos = [];
    
    public function setUser($user)
    {
        $this->atributos['user'] = $user;
        return $this;
    }

    public function create($quantidade = null)
    {
        return factory(Coordenador::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        
        return factory(Coordenador::class,$quantidade)->make($this->atributos);
    }
}