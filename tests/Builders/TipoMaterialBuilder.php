<?php

namespace Tests\Builders;

use App\TipoMaterial;

class TipoMaterialBuilder{
    protected $atributos = [];

    public function setDescricao($descricao)
    {
        $this->atributos["descricao"] = $descricao;
        return $this;
    }
    
    public function create($quantidade = null)
    {
        return factory(TipoMaterial::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(TipoMaterial::class,$quantidade)->make($this->atributos);
    }
}