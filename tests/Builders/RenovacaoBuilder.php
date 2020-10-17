<?php

namespace Tests\Builders;

use App\Renovacao;


class RenovacaoBuilder{
    protected $atributos = [];

    

    public function setDataPrevistaDevolucao($data)
    {
        $this->atributos['data_prevista_devolucao'] = $data;
        return $this;
    }

    public function setEmprestimo($emprestimo)
    {
        $this->atributos['emprestimo'] = $emprestimo;
        return $this;
    }

    public function setUsuarioRenovou($user)
    {
        $this->atributos['usuario_renovou'] = $user;
        return $this;
    }

   
    public function create($quantidade = null)
    {
  
        return factory(Renovacao::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Renovacao::class,$quantidade)->make($this->atributos);
    }
}