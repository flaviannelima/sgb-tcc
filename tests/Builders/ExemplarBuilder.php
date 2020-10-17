<?php

namespace Tests\Builders;

use App\Exemplar;

class ExemplarBuilder{
    protected $atributos = [];

    public function setCodigoBarras($codigoBarras)
    {
        $this->atributos["codigo_barras"] = $codigoBarras;
        return $this;
    }

    public function setEdicao($edicao)
    {
        $this->atributos["edicao"] = $edicao;
        return $this;
    }

    public function setAno($ano)
    {
        $this->atributos["ano"] = $ano;
        return $this;
    }

    public function setObra($obra)
    {
        $this->atributos["obra"] = $obra;
        return $this;
    }


    public function setObservacao($observacao)
    {
        $this->atributos["observacao"] = $observacao;
        return $this;
    }
    
    


    
    public function create($quantidade = null)
    {
        return factory(Exemplar::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Exemplar::class,$quantidade)->make($this->atributos);
    }
}