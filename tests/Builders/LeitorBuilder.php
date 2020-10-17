<?php

namespace Tests\Builders;

use App\Leitor;

class LeitorBuilder{
    protected $atributos = [];

    public function setCpf($cpf)
    {
        $this->atributos["cpf"] = $cpf;
        return $this;
    }

    public function setDataNascimento($data_nascimento)
    {
        $this->atributos["data_nascimento"] = $data_nascimento;
        return $this;
    }

    public function setEndereco($endereco)
    {
        $this->atributos["endereco"] = $endereco;
        return $this;
    }

    public function setTelefoneResidencial($telefone_residencial)
    {
        $this->atributos["telefone_residencial"] = $telefone_residencial;
        return $this;
    }

    public function setCelular($celular)
    {
        $this->atributos["celular"] = $celular;
        return $this;
    }

    public function setUser($user)
    {
        $this->atributos["user"] = $user;
        return $this;
    }
    
    public function create($quantidade = null)
    {
        return factory(Leitor::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Leitor::class,$quantidade)->make($this->atributos);
    }
}