<?php

namespace Tests\Builders;

use App\Editora;

class EditoraBuilder{
    protected $atributos = [];

    public function setNome($nome)
    {
        $this->atributos["nome"] = $nome;
        return $this;
    }
    
    public function create($quantidade = null)
    {
        return factory(Editora::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Editora::class,$quantidade)->make($this->atributos);
    }
}