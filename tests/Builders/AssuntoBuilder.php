<?php

namespace Tests\Builders;

use App\Assunto;

class AssuntoBuilder{
    protected $atributos = [];

    public function setDescricao($descricao)
    {
        $this->atributos["descricao"] = $descricao;
        return $this;
    }
    
    public function create($quantidade = null)
    {
        return factory(Assunto::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Assunto::class,$quantidade)->make($this->atributos);
    }
}