<?php

namespace Tests\Builders;

use App\Autor;

class AutorBuilder{
    protected $atributos = [];

    public function setNome($nome)
    {
        $this->atributos["nome"] = $nome;
        return $this;
    }
    
    public function create($quantidade = null)
    {
        return factory(Autor::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Autor::class,$quantidade)->make($this->atributos);
    }
}