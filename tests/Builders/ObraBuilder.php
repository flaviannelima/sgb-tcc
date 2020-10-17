<?php

namespace Tests\Builders;

use App\Obra;

class ObraBuilder{
    protected $atributos = [];

    public function setTipoMaterial($tipoMaterial)
    {
        $this->atributos["tipo_material"] = $tipoMaterial;
        return $this;
    }

    public function setCategoria($categoria)
    {
        $this->atributos["categoria"] = $categoria;
        return $this;
    }

    public function setTitulo($titulo)
    {
        $this->atributos["titulo"] = $titulo;
        return $this;
    }

    public function setAutores($autores)
    {
        $this->atributos["autores"] = $autores;
        return $this;
    }

    public function setEditora($editora)
    {
        $this->atributos["editora"] = $editora;
        return $this;
    }

    public function setAssuntos($assuntos)
    {
        $this->atributos["assuntos"] = $assuntos;
        return $this;
    }

    public function setVolume($volume)
    {
        $this->atributos["volume"] = $volume;
        return $this;
    }

    public function setObservacao($observacao)
    {
        $this->atributos["observacao"] = $observacao;
        return $this;
    }
    
    public function setLocalizacao($localizacao)
    {
        $this->atributos["localizacao"] = $localizacao;
        return $this;
    }


    
    public function create($quantidade = null)
    {
        return factory(Obra::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        return factory(Obra::class,$quantidade)->make($this->atributos);
    }
}