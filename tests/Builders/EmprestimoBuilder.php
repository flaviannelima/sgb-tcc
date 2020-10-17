<?php

namespace Tests\Builders;

use App\Emprestimo;
use App\User;

class EmprestimoBuilder{
    protected $atributos = [];

    public function setExemplar($exemplar)
    {
        $this->atributos["exemplar"] = $exemplar;
        return $this;
    }

    public function setLeitor($leitor)
    {
        $this->atributos["leitor"] = $leitor;
        return $this;
    }

    public function setDataPrevistaDevolucao($data)
    {
        $this->atributos['data_prevista_devolucao'] = $data;
        return $this;
    }

    public function setUsuarioEmprestou($usuario)
    {
        $this->atributos['usuario_emprestou'] = $usuario;
        return $this;
    }

 
   
    public function create($quantidade = null)
    {
  
        return factory(Emprestimo::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Emprestimo::class,$quantidade)->make($this->atributos);
    }
}