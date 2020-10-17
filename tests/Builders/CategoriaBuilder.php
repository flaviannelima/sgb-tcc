<?php

namespace Tests\Builders;

use App\Categoria;

class CategoriaBuilder{
    protected $atributos = [];

    public function setDescricao($descricao)
    {
        $this->atributos["descricao"] = $descricao;
        return $this;
    }
    
    public function create($quantidade = null)
    {
        return factory(Categoria::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Categoria::class,$quantidade)->make($this->atributos);
    }
}