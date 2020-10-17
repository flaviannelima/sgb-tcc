<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Builders\AssuntoBuilder;
use Tests\Builders\AtendenteBuilder;
use Tests\Builders\AutorBuilder;
use Tests\Builders\CategoriaBuilder;
use Tests\Builders\CoordenadorBuilder;
use Tests\Builders\EditoraBuilder;
use Tests\Builders\EmprestimoBuilder;
use Tests\Builders\ExemplarBuilder;
use Tests\Builders\LeitorBuilder;
use Tests\Builders\ObraBuilder;
use Tests\Builders\RenovacaoBuilder;
use Tests\Builders\TipoMaterialBuilder;
use Tests\Builders\UserBuilder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function autor()
    {
        return new AutorBuilder;
    }

    public function user()
    {
        return new UserBuilder;
    }

    public function coordenador()
    {
        return new CoordenadorBuilder;
    }

    public function atendente()
    {
        return new AtendenteBuilder;
    }

    public function tipoMaterial()
    {
        return new TipoMaterialBuilder;
    }

    public function assunto()
    {
        return new AssuntoBuilder;
    }

    public function editora()
    {
        return new EditoraBuilder;
    }

    public function obra()
    {
        return new ObraBuilder;
    }

    public function exemplar()
    {
        return new ExemplarBuilder;
    }

    public function leitor()
    {
        return new LeitorBuilder;
    }
    
    public function emprestimo()
    {
        return new EmprestimoBuilder;
    }

    public function renovacao()
    {
        return new RenovacaoBuilder;
    }

    public function categoria()
    {
        return new CategoriaBuilder;
    }
}
